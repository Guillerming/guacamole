<?php

declare(strict_types=1);

namespace Timecentric\UI\Endpoints\Auth\WithGoogle;

use Google\Client;
use Google\Service\Oauth2;
use Google\Service\Oauth2\Resource\Userinfo;
use Guacamole\Config\AppConfig;
use Guacamole\Config\Env;
use Guacamole\Database\Repositories\UserRepository;
use Guacamole\Helpers\CookieHelper;
use Guacamole\Helpers\DataRequesterHelper;
use Guacamole\Helpers\LogHelper;
use Guacamole\Helpers\StringHelper;
use Guacamole\Helpers\UserHelper;
use Guacamole\Http\Abstract\EndpointModel;
use Guacamole\Http\Response;
use Guacamole\Models\User;
use Throwable;
use Timecentric\Enums\CookieNames;
use Timecentric\Helpers\RouteHelper;
use Timecentric\Middlewares\GuestMiddleware;
use Timecentric\Router\RouteIds;

class WithGoogle extends EndpointModel {
    public function __construct() {
        self::addMiddleware(GuestMiddleware::class);
    }

    /**
     * Exchange authorization code for access token and get user data
     * 
     */
    private static function exchangeCodeForUserInfo(string $code): Userinfo|Response {
        try {
            $clientId = Env::get('GOOGLE_OAUTH_CLIENT_ID');
            assert(is_string($clientId));

            $clientSecret = Env::get('GOOGLE_OAUTH_CLIENT_SECRET');
            assert(is_string($clientSecret));
        } catch (Throwable $th) {
            $uuid = StringHelper::uuid();
            LogHelper::error(
                message: 'Missing Google OAuth environment variables',
                data: [
                    'code' => 'WG005',
                    'uuid' => $uuid,
                    'exception' => $th->getMessage(),
                    'clientId' => !empty(Env::get('GOOGLE_OAUTH_CLIENT_ID')),
                    'clientSecret' => !empty(Env::get('GOOGLE_OAUTH_CLIENT_SECRET')),
                ],
            );

            return new Response(
                status: 400,
                message: "WG005:{$uuid}",
            );
        }

        if (!$clientId || !$clientSecret) {
            $uuid = StringHelper::uuid();
            LogHelper::error(
                message: 'Empty Google OAuth credentials',
                data: [
                    'code' => 'WG006',
                    'uuid' => $uuid,
                    'clientId' => empty($clientId),
                    'clientSecret' => empty($clientSecret),
                ],
            );

            return new Response(
                status: 400,
                message: "WG006:{$uuid}",
            );
        }

        $client = new Client();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri(RouteHelper::link(RouteIds::LoginCallback));

        try {
            // Exchange code for access token
            $token = $client->fetchAccessTokenWithAuthCode($code);
            if (isset($token['error'])) {
                $uuid = StringHelper::uuid();
                LogHelper::error(
                    message: 'Google token exchange failed',
                    data: [
                        'code' => 'WG007',
                        'uuid' => $uuid,
                        'googleError' => $token['error'],
                        'fullTokenResponse' => $token,
                    ],
                );

                return new Response(
                    status: 400,
                    message: "WG007:{$uuid}",
                );
            }

            $client->setAccessToken($token);

            // Get user info from Google
            $userInfo = (new Oauth2($client))->userinfo;
            assert($userInfo instanceof Userinfo);

            return $userInfo;
        } catch (\Exception $e) {
            $uuid = StringHelper::uuid();
            LogHelper::error(
                message: 'Google OAuth API exception',
                data: [
                    'code' => 'WG008',
                    'uuid' => $uuid,
                    'message' => $e->getMessage(),
                    'exception' => [
                        'code' => $e->getCode(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]
                ],
            );

            return new Response(
                status: 400,
                message: "WG008:{$uuid}",
            );
        }
    }

    /**
     * Find existing user or create new one
     */
    private static function findOrCreateUser(Userinfo $userInfo): ?User {
        $repository = new UserRepository();

        // First, try to find user by Google ID
        if ($userInfo->get()->getId()) {
            $user = $repository->findByGoogleId($userInfo->get()->getId());
            if ($user) {
                return $user;
            }
        }

        // Then, try to find by email
        if ($userInfo->get()->getEmail()) {
            $user = $repository->findByEmail($userInfo->get()->getEmail());
            if ($user) {
                // Update existing user with Google ID if they don't have one
                // TODO: Implement updateUser method in UserRepository
                return $user;
            }
        }

        // Create new user
        $newUser = new User(
            id: 0, // Will be set by database
            name: $userInfo->get()->getName(),
            email: $userInfo->get()->getEmail(),
            googleId: $userInfo->get()->getId(),
            avatar: $userInfo->get()->getPicture(),
            isEnabled: true,
            subscriptionStatus: null
        );

        return $repository->create($newUser);
    }

    private static function processRequest(): Response {
        try {
            $cookieState = DataRequesterHelper::getCookieData(CookieNames::GoogleOAuthState->value);
            $urlState = DataRequesterHelper::getGetData('state');

            if (!$cookieState || !$urlState || $cookieState !== $urlState) {
                $uuid = StringHelper::uuid();
                LogHelper::error(
                    message: 'Invalid OAuth State',
                    data: [
                        'code' => 'WG001',
                        'uuid' => $uuid,
                        'cookieState' => $cookieState,
                        'urlState' => $urlState,
                        'match' => $cookieState == $urlState,
                    ],
                );

                return new Response(
                    status: 400,
                    message: "WG001:{$uuid}",
                );
            }

            $code = DataRequesterHelper::getGetData('code');
            if (!$code) {
                $uuid = StringHelper::uuid();
                LogHelper::error(
                    message: 'Authorization code not provided by Google',
                    data: [
                        'code' => 'WG002',
                        'uuid' => $uuid,
                    ],
                );

                return new Response(
                    status: 400,
                    message: "WG002:{$uuid}",
                );
            }

            $userInfo = self::exchangeCodeForUserInfo($code);
            if ($userInfo instanceof Response) {
                // There was an error retrieving data from Google
                return $userInfo;
            }

            $user = self::findOrCreateUser($userInfo);
            if (!$user) {
                $uuid = StringHelper::uuid();
                LogHelper::error(
                    message: 'Failed to create or find user in database',
                    data: [
                        'code' => 'WG003',
                        'uuid' => $uuid,
                        'id' => $userInfo->get()->getId(),
                        'email' => $userInfo->get()->getEmail(),
                        'name' => $userInfo->get()->getName(),
                    ],
                );

                return new Response(
                    status: 500,
                    message: "WG003:{$uuid}",
                );
            }

            $token = UserHelper::generateJwtForUser($user);
            if (!$token) {
                $uuid = StringHelper::uuid();
                LogHelper::error(
                    message: 'Failed to generate authentication token',
                    data: [
                        'code' => 'WG004',
                        'uuid' => $uuid,
                        'id' => $user->getId(),
                        'email' => $user->getEmail(),
                    ],
                );

                return new Response(
                    status: 500,
                    message: "WG004:{$uuid}",
                );
            }

            CookieHelper::set(
                name: CookieNames::Token,
                value: $token,
            );

            CookieHelper::delete(
                name: CookieNames::GoogleOAuthState,
            );

            return Response::redirect(RouteHelper::link(RouteIds::Dashboard));
        } catch (\Throwable $e) {
            $uuid = StringHelper::uuid();
            LogHelper::error(
                message: 'Unhandled exception in Google OAuth process',
                data: [
                    'code' => 'WG009',
                    'uuid' => $uuid,
                    'message' => $e->getMessage(),
                    'exception' => [
                        'code' => $e->getCode(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]
                ],
            );

            return new Response(
                status: 500,
                message: "WG009:{$uuid}",
            );
        }
    }

    public static function response(EndpointModel $endpointModel): Response {
        $response = self::processRequest();
        if ($response->status > 499) {
            return $response->redirect(
                url: AppConfig::baseUrl(
                    append: RouteIds::ServerError->value,
                    params: [
                        'code' => $response->message,
                        'status' => $response->status,
                    ],
                )->stringify(),
            );
        } else if ($response->status > 399) {
            return $response->redirect(
                url: AppConfig::baseUrl(
                    append: RouteIds::BadRequest->value,
                    params: [
                        'status' => $response->status,
                        'code' => $response->message,
                    ],
                )->stringify(),
            );
        }

        return $response;
    }
}
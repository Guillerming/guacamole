<?php

declare(strict_types=1);

namespace Guacamole\Helpers;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Guacamole\Config\Env;
use Guacamole\Database\Repositories\UserRepository;
use Guacamole\Models\User;

final class UserHelper {
    private static function getJwtSecret(): string {
        $secret = Env::get('JWT_SECRET');
        assert(is_string($secret));

        return $secret;
    }

    private static function getJwtExpiration(): int {
        $expiration = Env::get('JWT_EXPIRATION_HOURS');
        assert(is_string($expiration) || is_int($expiration));

        return (int) $expiration;
    }

    public static function current(): ?User {
        $token = DataRequesterHelper::getCookieData('token') ?? null;
        if (!$token) {
            return null;
        }

        try {
            $data = JWT::decode(
                jwt: $token,
                keyOrKeyArray: new Key(
                    keyMaterial: self::getJwtSecret(),
                    algorithm: 'HS256',
                )
            );
            if (!isset($data->user_id) || !isset($data->exp)) {
                return null;
            }
            assert(is_string($data->user_id) || is_int($data->user_id));
            assert(is_string($data->exp) || is_int($data->exp));
            $repo = new UserRepository();

            return $repo->findById((int)$data->user_id);
        } catch (Exception $exception) {
            // Invalid token
            return null;
        }
    }

    public static function generateJwt(): ?string {
        $user = self::current();
        if (!$user) {
            return null;
        }

        return JWT::encode(
            payload: [
                'user_id' => $user->getId(),
                'exp' => time() + (self::getJwtExpiration() * 3600),
            ],
            key: self::getJwtSecret(),
            alg: 'HS256',
        );
    }

    /**
     * Generate JWT token for a specific user (used during OAuth login)
     */
    public static function generateJwtForUser(User $user): string {
        return JWT::encode(
            payload: [
                'user_id' => $user->getId(),
                'exp' => time() + (self::getJwtExpiration() * 3600),
            ],
            key: self::getJwtSecret(),
            alg: 'HS256',
        );
    }

    public static function findByEmail(string $email): ?User {
        $repo = new UserRepository();

        return $repo->findByEmail($email);
    }

    public static function findByGoogleId(string $googleId): ?User {
        $repo = new UserRepository();

        return $repo->findByGoogleId($googleId);
    }
}
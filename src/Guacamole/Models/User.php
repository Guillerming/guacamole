<?php

declare(strict_types=1);

namespace Guacamole\Models;

final class User {
    private int $id;
    private string $name;
    private string $email;
    private ?string $googleId;
    private ?string $avatar;
    private bool $isEnabled;
    private ?string $subscriptionStatus;

    public function __construct(
        int $id,
        string $name,
        string $email,
        ?string $googleId = null,
        ?string $avatar = null,
        bool $isEnabled = true,
        ?string $subscriptionStatus = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->googleId = $googleId;
        $this->avatar = $avatar;
        $this->isEnabled = $isEnabled;
        $this->subscriptionStatus = $subscriptionStatus;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getGoogleId(): ?string {
        return $this->googleId;
    }

    public function getAvatar(): ?string {
        return $this->avatar;
    }

    public function isEnabled(): bool {
        return $this->isEnabled;
    }

    public function getSubscriptionStatus(): ?string {
        return $this->subscriptionStatus;
    }
}
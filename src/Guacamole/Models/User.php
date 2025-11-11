<?php

declare(strict_types=1);

namespace Guacamole\Models;

final class User {
    public function __construct(
        private int $id,
        private string $name,
        private string $email,
        private ?string $googleId = null,
        private ?string $avatar = null,
        private bool $isEnabled = true,
        private ?string $subscriptionStatus = null
    ) {
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
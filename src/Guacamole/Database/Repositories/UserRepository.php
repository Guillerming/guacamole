<?php

declare(strict_types=1);

namespace Guacamole\Database\Repositories;

use Guacamole\Database\Database;
use Guacamole\Models\User;
use PDO;

final class UserRepository {
    private ?PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function findById(int $id): ?User {
        if (!$this->db) {
            return null;
        }
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        assert(is_array($row));

        return $this->mapRowToUser($row);
    }

    public function findByEmail(string $email): ?User {
        if (!$this->db) {
            return null;
        }
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        assert(is_array($row));

        return $this->mapRowToUser($row);
    }

    public function findByGoogleId(string $googleId): ?User {
        if (!$this->db) {
            return null;
        }
        $stmt = $this->db->prepare('SELECT * FROM users WHERE google_id = :google_id LIMIT 1');
        $stmt->execute(['google_id' => $googleId]);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        assert(is_array($row));

        return $this->mapRowToUser($row);
    }

    public function create(User $user): ?User {
        if (!$this->db) {
            return null;
        }
        $stmt = $this->db->prepare(
            'INSERT INTO users (name, email, google_id, avatar, is_enabled, subscription_status) VALUES (:name, :email, :google_id, :avatar, :is_enabled, :subscription_status) RETURNING *'
        );
        $stmt->execute([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'google_id' => $user->getGoogleId(),
            'avatar' => $user->getAvatar(),
            'is_enabled' => $user->isEnabled(),
            'subscription_status' => $user->getSubscriptionStatus(),
        ]);
        $row = $stmt->fetch();
        assert(is_array($row));

        return $this->mapRowToUser($row);
    }

    /**
     * @param array<mixed,mixed> $row
     */
    private function mapRowToUser(array $row): User {
        assert(isset($row['id']) && (is_string($row['id']) || is_int($row['id'])));
        assert(isset($row['name']) && is_string($row['name']));
        assert(isset($row['email']) && is_string($row['email']));
        assert(array_key_exists('google_id', $row) && (is_string($row['google_id']) || is_null($row['google_id'])));
        assert(array_key_exists('avatar', $row) && (is_string($row['avatar']) || is_null($row['avatar'])));
        assert(isset($row['is_enabled']) && (is_bool($row['is_enabled']) || is_string($row['is_enabled'])));
        assert(array_key_exists('subscription_status', $row) && (is_string($row['subscription_status']) || is_null($row['subscription_status'])));

        return new User(
            (int)$row['id'],
            $row['name'],
            $row['email'],
            $row['google_id'],
            $row['avatar'],
            (bool)$row['is_enabled'],
            $row['subscription_status']
        );
    }
}
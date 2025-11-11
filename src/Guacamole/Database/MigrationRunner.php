<?php

declare(strict_types=1);

namespace Guacamole\Database;

use PDO;
use PDOException;

final class MigrationRunner {
    private PDO $db;
    private string $migrationsPath;

    public function __construct() {
        $connection = Database::getConnection();
        if ($connection === null) {
            throw new \RuntimeException('Database connection not available');
        }
        $this->db = $connection;
        $this->migrationsPath = __DIR__ . '/Migrations';
    }

    /**
     * Run all pending migrations
     *
     * @return array<string>
     */
    public function migrate(): array {
        $this->ensureMigrationsTableExists();
        $executed = [];

        $migrations = $this->getAvailableMigrations();
        $executedMigrations = $this->getExecutedMigrations();

        foreach ($migrations as $migration) {
            if (!in_array($migration->getVersion(), $executedMigrations, true)) {
                $this->executeMigration($migration);
                $executed[] = $migration->getName();
            }
        }

        return $executed;
    }

    /**
     * Rollback the last migration
     */
    public function rollback(): ?string {
        $this->ensureMigrationsTableExists();

        $lastMigration = $this->getLastExecutedMigration();
        if (!$lastMigration) {
            return null;
        }

        $migrations = $this->getAvailableMigrations();
        foreach ($migrations as $migration) {
            if ($migration->getVersion() === $lastMigration) {
                $this->rollbackMigration($migration);

                return $migration->getName();
            }
        }

        return null;
    }

    /**
     * Get migration status
     *
     * @return array<array{version: string, name: string, created_at: string, executed: bool}>
     */
    public function getStatus(): array {
        $this->ensureMigrationsTableExists();

        $migrations = $this->getAvailableMigrations();
        $executedMigrations = $this->getExecutedMigrations();

        $status = [];
        foreach ($migrations as $migration) {
            $status[] = [
                'version' => $migration->getVersion(),
                'name' => $migration->getName(),
                'created_at' => $migration->getCreatedAt()->format('Y-m-d H:i:s'),
                'executed' => in_array($migration->getVersion(), $executedMigrations, true)
            ];
        }

        return $status;
    }

    private function ensureMigrationsTableExists(): void {
        $sql = '
            CREATE TABLE IF NOT EXISTS migrations (
                version VARCHAR(255) PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ';

        $this->db->exec($sql);
    }

    /**
     * Auto-discover migrations by scanning the Migrations directory
     *
     * @return array<AbstractMigration>
     */
    private function getAvailableMigrations(): array {
        $migrations = [];

        if (!is_dir($this->migrationsPath)) {
            return $migrations;
        }

        $files = glob($this->migrationsPath . '/Migration*.php');
        if ($files === false) {
            return $migrations;
        }

        foreach ($files as $file) {
            $className = $this->getClassNameFromFile($file);

            if ($className && class_exists($className)) {
                try {
                    $migration = new $className();
                    if ($migration instanceof AbstractMigration) {
                        $migrations[] = $migration;
                    }
                } catch (\Throwable $e) {
                    // Skip invalid migrations, could log this
                    error_log("Failed to load migration {$className}: " . $e->getMessage());
                }
            }
        }

        // Sort by timestamp (version) ascending
        usort($migrations, fn($a, $b) => strcmp($a->getVersion(), $b->getVersion()));

        return $migrations;
    }

    private function getClassNameFromFile(string $file): ?string {
        $filename = basename($file, '.php');

        // Expected pattern: Migration{TIMESTAMP}{Description}
        if (preg_match('/^Migration\d{14}/', $filename)) {
            return "Guacamole\\Database\\Migrations\\{$filename}";
        }

        return null;
    }

    /**
     * @return array<string>
     */
    private function getExecutedMigrations(): array {
        $stmt = $this->db->prepare('SELECT version FROM migrations ORDER BY version');
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Ensure all values are strings
        return array_filter(array_map(function($value) {
            return is_string($value) ? $value : null;
        }, $result), function($value) {
            return $value !== null;
        });
    }

    private function getLastExecutedMigration(): ?string {
        $stmt = $this->db->prepare('SELECT version FROM migrations ORDER BY version DESC LIMIT 1');
        $stmt->execute();

        $result = $stmt->fetchColumn();

        return is_string($result) ? $result : null;
    }

    private function executeMigration(AbstractMigration $migration): void {
        try {
            $this->db->beginTransaction();

            // Execute the migration SQL
            $sql = $migration->up();
            $this->db->exec($sql);

            // Record the migration as executed
            $stmt = $this->db->prepare(
                'INSERT INTO migrations (version, name) VALUES (:version, :name)'
            );
            $stmt->execute([
                'version' => $migration->getVersion(),
                'name' => $migration->getName()
            ]);

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new \RuntimeException(
                "Failed to execute migration {$migration->getName()}: " . $e->getMessage()
            );
        }
    }

    private function rollbackMigration(AbstractMigration $migration): void {
        try {
            $this->db->beginTransaction();

            // Execute the rollback SQL
            $sql = $migration->down();
            $this->db->exec($sql);

            // Remove the migration record
            $stmt = $this->db->prepare('DELETE FROM migrations WHERE version = :version');
            $stmt->execute(['version' => $migration->getVersion()]);

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new \RuntimeException(
                "Failed to rollback migration {$migration->getName()}: " . $e->getMessage()
            );
        }
    }
}
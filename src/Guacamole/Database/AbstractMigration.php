<?php

declare(strict_types=1);

namespace Guacamole\Database;

abstract class AbstractMigration implements Migration {
    protected string $timestamp;

    public function __construct() {
        // Extract timestamp from class name (e.g., Migration20241111120000CreateUsersTable -> 20241111120000)
        $className = static::class;
        $shortClassName = substr($className, strrpos($className, '\\') + 1);

        // Extract timestamp from class name pattern: Migration{TIMESTAMP}{Description}
        if (preg_match('/^Migration(\d{14})/', $shortClassName, $matches)) {
            $this->timestamp = $matches[1];
        } else {
            throw new \InvalidArgumentException(
                "Migration class name must follow pattern Migration{YYYYMMDDHHMMSS}{Description}: {$className}"
            );
        }
    }

    /**
     * Get the migration timestamp (YYYYMMDDHHMMSS format)
     */
    public function getVersion(): string {
        return $this->timestamp;
    }

    /**
     * Get human-readable migration name from class name
     */
    public function getName(): string {
        $className = static::class;
        $shortClassName = substr($className, strrpos($className, '\\') + 1);

        // Remove Migration prefix and timestamp, convert to snake_case
        $name = preg_replace('/^Migration\d{14}/', '', $shortClassName);
        if ($name === null) {
            throw new \RuntimeException("Failed to extract name from migration class: {$className}");
        }

        $name = preg_replace('/([A-Z])/', '_$1', $name);
        if ($name === null) {
            throw new \RuntimeException("Failed to convert name to snake_case: {$className}");
        }

        return strtolower(trim($name, '_'));
    }

    /**
     * Get formatted creation date
     */
    public function getCreatedAt(): \DateTime {
        $date = \DateTime::createFromFormat('YmdHis', $this->timestamp);
        if ($date === false) {
            throw new \RuntimeException("Invalid timestamp format: {$this->timestamp}");
        }

        return $date;
    }
}
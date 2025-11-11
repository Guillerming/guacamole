<?php

declare(strict_types=1);

namespace Guacamole\Database;

interface Migration {
    /**
     * Execute the migration (create tables, add columns, etc.)
     */
    public function up(): string;

    /**
     * Reverse the migration (drop tables, remove columns, etc.)
     */
    public function down(): string;

    /**
     * Get the migration name/identifier
     */
    public function getName(): string;

    /**
     * Get the migration version number
     */
    public function getVersion(): string;
}
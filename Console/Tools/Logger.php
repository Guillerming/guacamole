<?php

declare(strict_types=1);

namespace Console\Tools;

use Console\Tools\Models\DatabaseData;

final class Logger {
    /**
     * Log database connection information
     */
    public static function logDatabaseConnection(): void {
        $host = DatabaseData::getHost();
        $port = DatabaseData::getPort();
        $dbname = DatabaseData::getName();
        $user = DatabaseData::getUser();

        Output::info('Database connection details:');
        Output::line("  Host: {$host}");
        Output::line("  Port: {$port}");
        Output::line("  Database: {$dbname}");
        Output::line("  User: {$user}");
        Output::line('');
    }

    /**
     * Log migration execution
     */
    public static function logMigrationExecution(string $migrationName, string $action): void {
        $timestamp = date('Y-m-d H:i:s');
        Output::info("[{$timestamp}] Migration {$action}: {$migrationName}");
    }

    /**
     * Log general info message with timestamp
     */
    public static function log(string $message, string $level = 'info'): void {
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[{$timestamp}] {$message}";

        match ($level) {
            'success' => Output::success($formattedMessage),
            'error' => Output::error($formattedMessage),
            'warning' => Output::warning($formattedMessage),
            default => Output::info($formattedMessage),
        };
    }
}
<?php

declare(strict_types=1);

namespace Console\Commands;

use Console\Tools\Input;
use Console\Tools\Logger;
use Console\Tools\Models\DatabaseData;
use Console\Tools\Output;
use Guacamole\Database\MigrationRunner;

final class MigrationsCommand {
    private ?MigrationRunner $migrationRunner = null;

    public function __construct() {
        // Don't initialize MigrationRunner in constructor
        // It will be initialized only when needed (not for init command)
    }

    /**
     * Get migration runner (lazy initialization)
     */
    private function getMigrationRunner(): MigrationRunner {
        if ($this->migrationRunner === null) {
            $this->migrationRunner = new MigrationRunner();
        }

        return $this->migrationRunner;
    }

    /**
     * Handle migrations command
     *
     * @param array<string> $args
     */
    public function handle(array $args): void {
        Logger::logDatabaseConnection();

        $action = $args[0] ?? '';

        match ($action) {
            'init' => $this->init(),
            'create' => $this->create(),
            'roll' => $this->roll(),
            'unroll' => $this->unroll(),
            'check' => $this->check(),
            default => $this->showHelp(),
        };
    }

    /**
     * Initialize database (create if not exists)
     */
    private function init(): void {
        Output::line('Initializing database...');
        Output::line('');

        try {
            // Get database connection details from environment with proper string casting
            $host = DatabaseData::getHost();
            $port = DatabaseData::getPort();
            $dbname = DatabaseData::getName();
            $username = DatabaseData::getUser();
            $password = DatabaseData::getPassword();

            // Connect to PostgreSQL server (postgres database is always present)
            $dsn = "pgsql:host={$host};port={$port};dbname=postgres";
            $pdo = new \PDO($dsn, $username, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Check if database exists
            $stmt = $pdo->prepare('SELECT 1 FROM pg_database WHERE datname = :dbname');
            $stmt->execute(['dbname' => $dbname]);

            if ($stmt->fetchColumn()) {
                Output::success("Database '{$dbname}' already exists");

                return;
            }

            // Create database - use quoted identifier for safety
            $pdo->exec("CREATE DATABASE \"{$dbname}\"");

            Logger::log("Database '{$dbname}' created successfully", 'success');
            Output::success("Database '{$dbname}' created successfully");
            Output::line('');
            Output::info('You can now run migrations:');
            Output::line('  php guacamole migrations roll');
        } catch (\PDOException $e) {
            Logger::log('Database initialization failed: ' . $e->getMessage(), 'error');
            Output::error('Database initialization failed: ' . $e->getMessage());
            Output::line('');
            Output::info('Make sure PostgreSQL is running and credentials are correct');
        }
    }

    /**
     * Create a new migration file
     */
    private function create(): void {
        Output::line('Creating new migration...');
        Output::line('');

        $description = Input::prompt('Enter migration description (e.g., "create posts table")');

        if (empty($description)) {
            Output::error('Migration description is required');

            return;
        }

        // Generate timestamp
        $timestamp = date('YmdHis');

        // Convert description to PascalCase
        $className = $this->descriptionToClassName($description);
        $fileName = "Migration{$timestamp}{$className}.php";
        $filePath = __DIR__ . '/../../src/Guacamole/Database/Migrations/' . $fileName;

        // Create migration file content
        $content = $this->generateMigrationTemplate($timestamp, $className);

        if (file_put_contents($filePath, $content) === false) {
            Output::error("Failed to create migration file: {$fileName}");

            return;
        }

        Logger::logMigrationExecution($fileName, 'created');
        Output::success("Migration created: {$fileName}");
        Output::line("File location: {$filePath}");
    }

    /**
     * Execute pending migrations
     */
    private function roll(): void {
        Output::line('Executing pending migrations...');
        Output::line('');

        try {
            $executed = $this->getMigrationRunner()->migrate();

            if (empty($executed)) {
                Output::info('No pending migrations found');

                return;
            }

            foreach ($executed as $migration) {
                Logger::logMigrationExecution($migration, 'executed');
                Output::success("Executed: {$migration}");
            }

            Output::line('');
            Output::success(count($executed) . ' migration(s) executed successfully');
        } catch (\Exception $e) {
            Logger::log('Migration execution failed: ' . $e->getMessage(), 'error');
            Output::error('Migration execution failed: ' . $e->getMessage());
        }
    }

    /**
     * Rollback last migration
     */
    private function unroll(): void {
        Output::line('Rolling back last migration...');
        Output::line('');

        $confirm = Input::confirm('Are you sure you want to rollback the last migration?', false);
        if (!$confirm) {
            Output::info('Rollback cancelled');

            return;
        }

        try {
            $rolledBack = $this->getMigrationRunner()->rollback();

            if ($rolledBack === null) {
                Output::info('No migrations to rollback');

                return;
            }

            Logger::logMigrationExecution($rolledBack, 'rolled back');
            Output::success("Rolled back: {$rolledBack}");
        } catch (\Exception $e) {
            Logger::log('Migration rollback failed: ' . $e->getMessage(), 'error');
            Output::error('Migration rollback failed: ' . $e->getMessage());
        }
    }

    /**
     * Check migration status
     */
    private function check(): void {
        Output::line('Checking migration status...');
        Output::line('');

        try {
            $status = $this->getMigrationRunner()->getStatus();

            if (empty($status)) {
                Output::info('No migrations found');

                return;
            }

            // Prepare table data
            $headers = ['Version', 'Name', 'Created At', 'Status'];
            $rows = [];
            $pendingCount = 0;

            foreach ($status as $migration) {
                $statusText = $migration['executed'] ? 'Executed' : 'Pending';
                if (!$migration['executed']) {
                    $pendingCount++;
                }

                $rows[] = [
                    $migration['version'],
                    $migration['name'],
                    $migration['created_at'],
                    $statusText
                ];
            }

            Output::table($headers, $rows);
            Output::line('');

            if ($pendingCount > 0) {
                Output::warning("{$pendingCount} migration(s) pending execution");
            } else {
                Output::success('All migrations are up to date');
            }
        } catch (\Exception $e) {
            Logger::log('Migration status check failed: ' . $e->getMessage(), 'error');
            Output::error('Migration status check failed: ' . $e->getMessage());
        }
    }

    /**
     * Show help for migrations command
     */
    private function showHelp(): void {
        Output::line('Guacamole Migrations');
        Output::line('');
        Output::line('Usage:');
        Output::line('  php guacamole migrations <action>');
        Output::line('');
        Output::line('Available actions:');
        Output::line('  init    - Initialize database (create if not exists)');
        Output::line('  create  - Create a new migration file');
        Output::line('  roll    - Execute all pending migrations');
        Output::line('  unroll  - Rollback the last executed migration');
        Output::line('  check   - Show migration status');
        Output::line('');
        Output::line('Examples:');
        Output::line('  php guacamole migrations init');
        Output::line('  php guacamole migrations create');
        Output::line('  php guacamole migrations roll');
        Output::line('  php guacamole migrations check');
    }

    /**
     * Convert description to PascalCase class name
     */
    private function descriptionToClassName(string $description): string {
        // Clean and split the description
        $words = preg_split('/[\s_-]+/', strtolower($description));
        if ($words === false) {
            throw new \RuntimeException('Failed to parse description');
        }

        // Convert to PascalCase
        return implode('', array_map('ucfirst', array_filter($words)));
    }

    /**
     * Generate migration template
     */
    private function generateMigrationTemplate(string $timestamp, string $className): string {
        return <<<PHP
<?php

declare(strict_types=1);

namespace Guacamole\Database\Migrations;

use Guacamole\Database\AbstractMigration;

final class Migration{$timestamp}{$className} extends AbstractMigration {
    public function up(): string {
        return "
            -- Write your migration SQL here
            -- Example:
            -- CREATE TABLE example (
            --     id SERIAL PRIMARY KEY,
            --     name VARCHAR(255) NOT NULL,
            --     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            -- );
        ";
    }

    public function down(): string {
        return "
            -- Write your rollback SQL here
            -- Example:
            -- DROP TABLE IF EXISTS example CASCADE;
        ";
    }
}
PHP;
    }
}
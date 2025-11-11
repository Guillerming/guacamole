<?php

declare(strict_types=1);

namespace Guacamole\Database\Migrations;

use Guacamole\Database\AbstractMigration;

final class Migration20241111120000CreateUsersTable extends AbstractMigration {
    public function up(): string {
        return '
            CREATE TABLE users (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                google_id VARCHAR(255) NULL,
                avatar VARCHAR(500) NULL,
                is_enabled BOOLEAN NOT NULL DEFAULT true,
                subscription_status VARCHAR(100) NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );

            -- Indexes for better performance
            CREATE INDEX idx_users_email ON users(email);
            CREATE INDEX idx_users_google_id ON users(google_id) WHERE google_id IS NOT NULL;
        ';
    }

    public function down(): string {
        return 'DROP TABLE IF EXISTS users CASCADE;';
    }
}
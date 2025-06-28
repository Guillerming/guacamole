<?php

declare(strict_types=1);

namespace Guacamole\Database;

use Guacamole\Config\Env;
use PDO;

class Database {
    private static ?PDO $connection = null;

    public static function connect(): void {
        if (self::$connection) {
            return;
        }
        $host = is_string(Env::get('POSTGRES_HOST')) ? Env::get('POSTGRES_HOST') : '';
        $port = is_string(Env::get('POSTGRES_PORT')) ? Env::get('POSTGRES_PORT') : '';
        $dbname = is_string(Env::get('POSTGRES_NAME')) ? Env::get('POSTGRES_NAME') : '';
        $username = is_string(Env::get('POSTGRES_USER')) ? Env::get('POSTGRES_USER') : '';
        $password = is_string(Env::get('POSTGRES_PASSWORD')) ? Env::get('POSTGRES_PASSWORD') : '';
        self::$connection = new PDO(
            dsn: "pgsql:host={$host};port={$port};dbname={$dbname}",
            username: $username,
            password: $password,
        );
    }

    public static function isConnected(): bool {
        self::connect();

        return (bool) self::$connection;
    }

    public static function getConnection(): ?PDO {
        self::connect();

        return self::$connection;
    }
}
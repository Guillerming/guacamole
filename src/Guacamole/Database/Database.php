<?php

declare(strict_types=1);

namespace Guacamole\Database;

use Guacamole\Config\Env;
use Guacamole\Helpers\LogHelper;
use PDO;

class Database {

    private static ?PDO $connection = null;

    public static function connect(): void {
        if (self::$connection) {
            return;
        }
        $host = Env::get('POSTGRES_HOST');
        $port = Env::get('POSTGRES_PORT');
        $dbname = Env::get('POSTGRES_NAME');
        self::$connection = new PDO(
            dsn: "pgsql:host={$host};port={$port};dbname={$dbname}",
            username: Env::get('POSTGRES_USER'),
            password: Env::get('POSTGRES_PASSWORD'),
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
<?php

declare(strict_types=1);

namespace Guacamole\Helpers;

use PDO;

class DbHelper {
    private static ?PDO $pdo = null;

    public static function connect(): PDO {
        if (self::$pdo === null) {
            $host = getenv('POSTGRES_HOST') ?: '';
            $db   = getenv('POSTGRES_DB') ?: '';
            $user = getenv('POSTGRES_USER') ?: '';
            $pass = getenv('POSTGRES_PASSWORD') ?: '';
            $dsn = "pgsql:host=$host;dbname=$db";
            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }

        return self::$pdo;
    }
}

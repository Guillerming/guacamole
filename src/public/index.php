<?php

declare(strict_types=1);

try {
    require_once __DIR__ . '/../../vendor/autoload.php';
    require_once __DIR__ . '/../Guacamole/Guacamole.php';
    require_once __DIR__ . '/../Site/Bootstrap.php';
} catch (Throwable $th) {
    echo json_encode([
        'error' => $th->getMessage(), // TODO: Prevent leaks!
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

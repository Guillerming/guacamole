<?php

declare(strict_types=1);

try {
    require_once __DIR__ . '/../../vendor/autoload.php';
    require_once __DIR__ . '/../Guacamole/Guacamole.php';
    require_once __DIR__ . '/../Timecentric/Bootstrap.php';
} catch (Throwable $th) {
    $stream = fopen('php://stderr', 'w');
    assert(gettype($stream) == 'resource');
    fwrite($stream, $th->getMessage()."\n"); ?>

        <h1>500 - Fatal Error</h1>
        <p>There was an unexpected error. Try again in a few minutes. Contact us if the problem persist.</p>

    <?php
}

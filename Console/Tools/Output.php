<?php

declare(strict_types=1);

namespace Console\Tools;

final class Output {
    // ANSI Color codes
    private const COLORS = [
        'black' => '0;30',
        'red' => '0;31',
        'green' => '0;32',
        'yellow' => '0;33',
        'blue' => '0;34',
        'magenta' => '0;35',
        'cyan' => '0;36',
        'white' => '0;37',
        'bright_red' => '1;31',
        'bright_green' => '1;32',
        'bright_yellow' => '1;33',
        'bright_blue' => '1;34',
        'bright_magenta' => '1;35',
        'bright_cyan' => '1;36',
        'bright_white' => '1;37',
    ];

    /**
     * Output colored text
     */
    public static function color(string $text, string $color): string {
        if (!isset(self::COLORS[$color])) {
            return $text;
        }

        return "\033[" . self::COLORS[$color] . "m{$text}\033[0m";
    }

    /**
     * Output success message (green)
     */
    public static function success(string $message): void {
        echo self::color("✓ {$message}", 'bright_green') . "\n";
    }

    /**
     * Output error message (red)
     */
    public static function error(string $message): void {
        echo self::color("✗ {$message}", 'bright_red') . "\n";
    }

    /**
     * Output warning message (yellow)
     */
    public static function warning(string $message): void {
        echo self::color("⚠ {$message}", 'bright_yellow') . "\n";
    }

    /**
     * Output info message (blue)
     */
    public static function info(string $message): void {
        echo self::color("ℹ {$message}", 'bright_blue') . "\n";
    }

    /**
     * Output plain text
     */
    public static function line(string $message = ''): void {
        echo $message . "\n";
    }

    /**
     * Output table with headers and rows
     *
     * @param array<string>        $headers
     * @param array<array<string>> $rows
     */
    public static function table(array $headers, array $rows): void {
        // Calculate column widths
        $widths = [];
        foreach ($headers as $i => $header) {
            $widths[$i] = strlen($header);
        }

        foreach ($rows as $row) {
            foreach ($row as $i => $cell) {
                $widths[$i] = max($widths[$i] ?? 0, strlen($cell));
            }
        }

        // Print top border
        echo '┌';
        $lastIndex = count($widths) - 1;
        foreach ($widths as $i => $width) {
            echo str_repeat('─', $width + 2);
            echo $i === $lastIndex ? '┐' : '┬';
        }
        echo "\n";

        // Print headers
        echo '│';
        foreach ($headers as $i => $header) {
            echo ' ' . str_pad($header, $widths[$i]) . ' │';
        }
        echo "\n";

        // Print middle border
        echo '├';
        foreach ($widths as $i => $width) {
            echo str_repeat('─', $width + 2);
            echo $i === $lastIndex ? '┤' : '┼';
        }
        echo "\n";

        // Print rows
        foreach ($rows as $row) {
            echo '│';
            foreach ($row as $i => $cell) {
                echo ' ' . str_pad($cell, $widths[$i]) . ' │';
            }
            echo "\n";
        }

        // Print bottom border
        echo '└';
        foreach ($widths as $i => $width) {
            echo str_repeat('─', $width + 2);
            echo $i === $lastIndex ? '┘' : '┴';
        }
        echo "\n";
    }
}
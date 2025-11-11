<?php

declare(strict_types=1);

namespace Console\Tools;

final class Input {
    /**
     * Prompt user for input with a question
     */
    public static function prompt(string $question, string $default = ''): string {
        $defaultText = $default ? " [{$default}]" : '';
        echo "{$question}{$defaultText}: ";

        $handle = fopen('php://stdin', 'r');
        if ($handle === false) {
            throw new \RuntimeException('Unable to open stdin for input');
        }

        $input = fgets($handle);
        fclose($handle);

        if ($input === false) {
            return $default;
        }

        $trimmedInput = trim($input);

        return $trimmedInput ?: $default;
    }

    /**
     * Prompt user for confirmation (y/n)
     */
    public static function confirm(string $question, bool $default = false): bool {
        $defaultText = $default ? '[Y/n]' : '[y/N]';
        $answer = self::prompt("{$question} {$defaultText}");

        if (empty($answer)) {
            return $default;
        }

        return in_array(strtolower($answer), ['y', 'yes', '1', 'true'], true);
    }

    /**
     * Prompt user to choose from options
     *
     * @param array<string> $options
     */
    public static function choose(string $question, array $options, string $default = ''): string {
        echo "{$question}\n";
        foreach ($options as $i => $option) {
            echo '  ' . ($i + 1) . ") {$option}\n";
        }

        $choice = self::prompt('Choose option', $default);

        // If numeric, convert to option
        if (is_numeric($choice)) {
            $index = (int)$choice - 1;
            if (isset($options[$index])) {
                return $options[$index];
            }
        }

        // If direct match
        if (in_array($choice, $options, true)) {
            return $choice;
        }

        return $default;
    }
}
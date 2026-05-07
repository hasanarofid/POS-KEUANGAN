<?php
require 'vendor/autoload.php';

// Mocking some parts if needed, but these are static methods
class Tester {
    public static function parseNumber($value): float
    {
        if (is_null($value) || $value === '') {
            return 0;
        }

        if (is_float($value) || is_int($value)) {
            return (float)$value;
        }

        $value = (string)$value;
        $value = str_replace(['Rp', ' ', "\xc2\xa0"], '', $value);

        if ($value === '') return 0;

        $dotCount = substr_count($value, '.');
        $commaCount = substr_count($value, ',');

        if ($dotCount > 0 && $commaCount > 0) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
            return (float)$value;
        }

        if ($dotCount > 0 && $commaCount === 0) {
            if ($dotCount > 1) {
                return (float)str_replace('.', '', $value);
            }
            $parts = explode('.', $value);
            if (strlen($parts[1]) === 3) {
                return (float)str_replace('.', '', $value);
            }
            return (float)$value;
        }

        if ($commaCount > 0 && $dotCount === 0) {
            if ($commaCount > 1) {
                return (float)str_replace(',', '', $value);
            }
            return (float)str_replace(',', '.', $value);
        }

        return (float)$value;
    }

    public static function formatNumber($value, $decimals = 2): string
    {
        if ($value === null || $value === '') return '';
        $parsed = self::parseNumber($value);

        if ($decimals === 0) {
            return number_format($parsed, 0, ',', '.');
        }

        $formatted = number_format($parsed, $decimals, ',', '.');
        $parts = explode(',', $formatted);
        if (count($parts) === 2) {
            $decimal = rtrim($parts[1], '0');
            if ($decimal === '') {
                return $parts[0];
            }
            return $parts[0] . ',' . $decimal;
        }
        return $formatted;
    }
}

$testValues = [
    '5.990.000',
    '5.990',
    '1.235.000',
    '185.000',
    '0',
    0,
    5990000,
    '5,990,000', // Should probably be handled?
];

foreach ($testValues as $val) {
    $parsed = Tester::parseNumber($val);
    $formatted = Tester::formatNumber($parsed);
    echo "Input: " . var_export($val, true) . " -> Parsed: $parsed -> Formatted: $formatted\n";
}

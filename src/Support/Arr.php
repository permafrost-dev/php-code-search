<?php

namespace Permafrost\PhpCodeSearch\Support;

class Arr
{
    public static function matchesAnyPattern(string $str, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            // add delimiters if they're missing
            if ($pattern[0] !== $pattern[strlen($pattern) - 1]) {
                $pattern = '~' . $pattern . '~';
            }

            if (preg_match($pattern, $str) === 1) {
                return true;
            }
        }

        return false;
    }

    public static function matches(string $str, array $values, bool $allowRegex = true): bool
    {
        foreach ($values as $value) {
            if ($allowRegex && self::isRegexPattern($value)) {
                if (preg_match($value, $str) === 1) {
                    return true;
                }
            }

            if ($str === $value) {
                return true;
            }
        }

        return false;
    }

    protected static function isRegexPattern(string $str): bool
    {
        return $str[0] === '/' && $str[strlen($str) - 1] === '/' && strlen($str) > 1;
    }
}

<?php

namespace Permafrost\PhpCodeSearch\Support;

use Permafrost\PhpCodeSearch\Support\Collections\Collection;

/** @codeCoverageIgnore */
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

    public static function matchesAny($strings, array $values, bool $allowRegex = true): bool
    {
        if (! is_array($strings)) {
            $strings = [$strings];
        }

        return collect($strings)->map(function (string $str) use ($values, $allowRegex) {
            return self::matches($str, $values, $allowRegex);
        })->filter(function ($value) {
            return $value;
        })->count() > 0;
    }

    /**
     * Determine whether the given value is array accessible.
     *
     * @param  mixed  $value
     * @return bool
     */
    public static function accessible($value): bool
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }

    /**
     * Collapse an array of arrays into a single array.
     *
     * @param iterable $array
     * @return array
     */
    public static function collapse(iterable $array): array
    {
        $results = [];

        foreach ($array as $values) {
            if ($values instanceof Collection) {
                $values = $values->all();
            } elseif (! is_array($values)) {
                continue;
            }

            $results[] = $values;
        }

        return array_merge([], ...$results);
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|int  $key
     * @return bool
     */
    public static function exists($array, $key): bool
    {
        if ($array instanceof Collection) {
            return $array->has($key);
        }

        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * Pluck an array of values from an array.
     *
     * @param  iterable  $array
     * @param  string|array|int|null  $value
     * @param  string|array|null  $key
     * @return array
     */
    public static function pluck($array, $value, $key = null): array
    {
        $results = [];

        [$value, $key] = static::explodePluckParameters($value, $key);

        foreach ($array as $item) {
            $itemValue = data_get($item, $value);

            // If the key is "null", we will just append the value to the array and keep
            // looping. Otherwise we will key the array using the value of the key we
            // received from the developer. Then we'll return the final array form.
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = data_get($item, $key);

                if (is_object($itemKey) && method_exists($itemKey, '__toString')) {
                    $itemKey = (string) $itemKey;
                }

                $results[$itemKey] = $itemValue;
            }
        }

        return $results;
    }

    /**
     * Explode the "value" and "key" arguments passed to "pluck".
     *
     * @param  string|array  $value
     * @param  string|array|null  $key
     * @return array
     */
    protected static function explodePluckParameters($value, $key): array
    {
        $value = is_string($value) ? explode('.', $value) : $value;

        $key = is_null($key) || is_array($key) ? $key : explode('.', $key);

        return [$value, $key];
    }

    public static function where(array $array, callable $callback): array
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }

    protected static function isRegexPattern(string $str): bool
    {
        return strpos($str, '/') === 0
            && substr($str, -1) === '/'
            && strlen($str) > 1;
    }
}

<?php

use Permafrost\PhpCodeSearch\Support\Arr;
use Permafrost\PhpCodeSearch\Support\Collections\Collection;

if (! function_exists('opt')) {
    /**
     * "optional" helper function
     * @param $value
     * @return mixed|__anonymous@237
     */
    function opt($value)
    {
        if (! $value) {
            return new class {
                public function __get($name)
                {
                    return null;
                }

                public function __call($name, $arguments)
                {
                    return null;
                }
            };
        }

        return val($value);
    }
}

/** @codeCoverageIgnore */
if (! function_exists('get_data')) {
    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param  mixed  $target
     * @param  string|array|int|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    function get_data($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        foreach ($key as $i => $segment) {
            unset($key[$i]);

            if (is_null($segment)) {
                return $target;
            }

            if ($segment === '*') {
                if ($target instanceof Collection) {
                    $target = $target->all();
                } elseif (! is_iterable($target)) {
                    return val($default);
                }

                $result = [];

                foreach ($target as $item) {
                    $result[] = get_data($item, $key);
                }

                return in_array('*', $key) ? Arr::collapse($result) : $result;
            }

            if (Arr::accessible($target) && Arr::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return val($default);
            }
        }

        return $target;
    }
}

/** @codeCoverageIgnore */
if (! function_exists('val')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    function val($value, ...$args)
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}

if (! function_exists('collection')) {
    function collection($items)
    {
        return new Collection($items);
    }
}

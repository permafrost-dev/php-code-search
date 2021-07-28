<?php

if (! function_exists('optional')) {
    function optional($value) {
        if (! $value) {
            return new class {
                public function __get($name, $args) {
                    return null;
                }
                public function __call($name, $arguments)
                {
                    return null;
                }
            };
        }

        return $value;
    }
}

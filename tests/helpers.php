<?php

if (! function_exists('tests_path')) {
    function tests_path(string $path = ''): string
    {
        return implode(DIRECTORY_SEPARATOR, [__DIR__, $path]);
    }
}

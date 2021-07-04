<?php
    ray('12345');

    printf("%s\n", strtolower('TEST'));

    echo strtoupper('test') . PHP_EOL;

    function test1()
    {
        Ray::rateLimiter()->count(5);
    }

    $obj = new MyClass();

    $obj->withData([123])->send();

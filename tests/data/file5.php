<?php

function testFive(?string $one)
{
    return 'one';
}

class MyClassFive
{
    public static function one(?int $value): ?int
    {
        return 1;
    }
}

MyClassFive::one(null);

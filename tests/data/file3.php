<?php

class MyClass1
{
    public $id = 1;

    public function getData(): string
    {
        return 'test';
    }

    protected function callExternalApi(string $endpoint, int $count = 1): array
    {
        return [1, 2];
    }
}

$obj = new MyClass2();

$obj->getData();

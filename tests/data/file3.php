<?php

class MyClass1
{
    public $id = 1;

    public function getData(): string
    {
        return 'test';
    }
}

$obj = new MyClass2();

$obj->getData();

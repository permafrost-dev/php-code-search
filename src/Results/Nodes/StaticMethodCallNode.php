<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

class StaticMethodCallNode implements ResultNode
{
    /** @var string */
    public $className;

    /** @var string */
    public $methodName;

    /** @var string */
    public $name;

    public function __construct(string $className, string $methodName)
    {
        $this->className = $className;
        $this->methodName = $methodName;
        $this->name = $this->name();
    }

    public static function create(string $className, string $methodName): self
    {
        return new static(...func_get_args());
    }

    public function name(): string
    {
        return "{$this->className}::{$this->methodName}";
    }
}

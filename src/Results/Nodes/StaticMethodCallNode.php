<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Results\Nodes\Traits\TransformsArguments;

class StaticMethodCallNode implements ResultNode
{
    use TransformsArguments;

    /** @var string */
    public $className;

    /** @var string */
    public $methodName;

    /** @var string */
    public $name;

    /** @var array|ResultNode[]|ValueNode[] */
    public $args;

    public function __construct(string $className, string $methodName, $args)
    {
        $this->className = $className;
        $this->methodName = $methodName;
        $this->args = $this->transformArgumentsToNodes($args);
        $this->name = $this->name();
    }

    public static function create(string $className, string $methodName, $args): self
    {
        return new static(...func_get_args());
    }

    public function name(): string
    {
        return "{$this->className}::{$this->methodName}";
    }
}

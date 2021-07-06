<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

class MethodCallNode implements ResultNode
{
    /** @var string */
    public $variableName;

    /** @var string */
    public $methodName;

    /** @var string */
    public $name;

    public function __construct(string $variableName, string $methodName)
    {
        $this->variableName = $variableName;
        $this->methodName = $methodName;
        $this->name = $this->name();
    }

    public static function create(string $variableName, string $methodName): self
    {
        return new static(...func_get_args());
    }

    public function name(): string
    {
        return "\${$this->variableName}->{$this->methodName}";
    }
}

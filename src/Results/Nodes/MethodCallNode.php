<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Results\Nodes\Traits\TransformsArguments;

class MethodCallNode implements ResultNode
{
    use TransformsArguments;

    /** @var string */
    public $variableName;

    /** @var string */
    public $methodName;

    /** @var string */
    public $name;

    /** @var array|ResultNode[]|ValueNode[] */
    public $args;

    public function __construct(string $variableName, string $methodName, $args)
    {
        $this->variableName = $variableName;
        $this->methodName = $methodName;
        $this->args = $this->transformArgumentsToNodes($args);
        $this->name = $this->name();
    }

    public static function create(string $variableName, string $methodName, $args): self
    {
        return new static(...func_get_args());
    }

    public function name(): string
    {
        return "\${$this->variableName}->{$this->methodName}";
    }
}

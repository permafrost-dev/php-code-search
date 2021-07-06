<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

class AssignmentNode implements ResultNode
{
    /** @var string */
    public $variableName;

    /** @var mixed */
    public $value;

    /** @var string */
    public $name;

    public function __construct(string $variableName, $value)
    {
        $this->variableName = $variableName;
        $this->value = $value;
        $this->name = $this->name();
    }

    public static function create(string $variableName, $value): self
    {
        return new static(...func_get_args());
    }

    public function name(): string
    {
        return $this->variableName;
    }
}

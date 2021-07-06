<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

class FunctionCallNode implements ResultNode
{
    /** @var string */
    public $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function create(string $name): self
    {
        return new static(...func_get_args());
    }

    public function name(): string
    {
        return $this->name;
    }
}

<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Results\Nodes\Traits\TransformsArguments;

class FunctionCallNode implements ResultNode
{
    use TransformsArguments;

    /** @var string */
    public $name;

    /** @var array|ResultNode[]|ValueNode[] */
    public $args;

    public function __construct(string $name, $args)
    {
        $this->name = $name;
        $this->args = $this->transformArgumentsToNodes($args);
    }

    public static function create(string $name, $args): self
    {
        return new static(...func_get_args());
    }

    public function name(): string
    {
        return $this->name;
    }


}

<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\TransformsArguments;

class FunctionCallNode implements ResultNode
{
    use HasLocation;
    use TransformsArguments;

    /** @var string */
    public $name;

    /** @var array|ResultNode[]|ValueNode[] */
    public $args;

    public function __construct(string $name, $args, CodeLocation $location)
    {
        $this->name = $name;
        $this->args = $this->transformArgumentsToNodes($args);
        $this->location = $location;
    }

    public static function create(string $name, $args, CodeLocation $location): self
    {
        return new static(...func_get_args());
    }

    public function name(): string
    {
        return $this->name;
    }
}

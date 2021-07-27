<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;

class VariableNode implements ResultNode
{
    use HasLocation;

    /** @var string */
    public $name;

    public function __construct(string $name, CodeLocation $location)
    {
        $this->name = $name;
        $this->location = $location;
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

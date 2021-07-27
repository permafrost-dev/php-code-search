<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Support\Transformer;

class AssignmentNode implements ResultNode, ValueNode
{
    use HasLocation;

    /** @var string */
    public $variableName;

    /** @var mixed */
    public $value;

    /** @var string */
    public $name;

    public function __construct(string $variableName, $value, CodeLocation $location)
    {
        $this->variableName = $variableName;
        $this->value = Transformer::parserNodeToResultNode($value);
        $this->name = $this->name();
        $this->location = $location;
    }

    public static function create(string $variableName, $value): self
    {
        return new static(...func_get_args());
    }

    public function name(): string
    {
        return $this->variableName;
    }

    public function value()
    {
        return $this->value;
    }
}

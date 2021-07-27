<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Support\Transformer;
use PhpParser\Node;

class AssignmentNode implements ResultNode, ValueNode
{
    use HasLocation;

    /** @var string */
    public $variableName;

    /** @var mixed */
    public $value;

    /** @var string */
    public $name;

    public function __construct(Node\Expr\Assign $node)
    {
        $this->variableName = $node->var->name;
        $this->value = Transformer::parserNodeToResultNode($node->expr);
        $this->location = GenericCodeLocation::createFromNode($node);
        $this->name = $this->name();
    }

    public static function create(Node\Expr\Assign $node): self
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

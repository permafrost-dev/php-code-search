<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Support\Transformer;
use PhpParser\Node\Expr\AssignOp;

class AssignmentOperationNode implements ResultNode, ValueNode
{
    use HasLocation;

    /** @var string */
    public $name;

    /** @var mixed|ResultNode|ValueNode */
    public $value;

    public function __construct(AssignOp $node, CodeLocation $location)
    {
        $this->name = $node->var->name;
        $this->value = Transformer::parserNodeToResultNode($node->expr);
        $this->location = $location;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value()
    {
        return $this->value;
    }
}

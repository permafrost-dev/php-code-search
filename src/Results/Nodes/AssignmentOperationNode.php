<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Support\Transformer;
use PhpParser\Node\Expr\AssignOp;

class AssignmentOperationNode implements ResultNode, ValueNode
{
    /** @var string */
    public $name;

    /** @var mixed|ResultNode|ValueNode */
    public $value;

    public function __construct(AssignOp $node)
    {
        $this->name = $node->var->name;
        $this->value = Transformer::parserNodeToResultNode($node->expr);
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

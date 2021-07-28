<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Support\ExpressionTransformer;
use PhpParser\Node\Expr\AssignOp;

class AssignmentOperationNode implements ResultNode, ValueNode
{
    use HasLocation;

    /** @var string */
    public $name;

    /** @var mixed|ResultNode|ValueNode */
    public $value;

    public function __construct(AssignOp $node)
    {
        $this->name = $node->var->name;
        $this->value = ExpressionTransformer::parserNodeToResultNode($node->expr);
        $this->location = GenericCodeLocation::createFromNode($node);
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

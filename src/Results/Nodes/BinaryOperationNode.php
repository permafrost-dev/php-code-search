<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Support\ExpressionTransformer;
use PhpParser\Node\Expr\BinaryOp;

class BinaryOperationNode implements OperationNode, ValueNode
{
    use HasLocation;

    /** @var string */
    public $symbol;

    /** @var mixed|ResultNode|ValueNode */
    public $left;

    /** @var mixed|ResultNode|ValueNode */
    public $right;

    /** @var string */
    public $value;

    public function __construct(BinaryOp $node)
    {
        $this->symbol = $node->getOperatorSigil();
        $this->left = ExpressionTransformer::parserNodeToResultNode($node->left);
        $this->right = ExpressionTransformer::parserNodeToResultNode($node->right);

        //        $this->value = '';
        //        if (property_exists($this->left, 'value') && property_exists($this->right, 'value')) {
        $leftValue = '';
        $rightValue = '';

        $this->value = ExpressionTransformer::binaryOperationNodeToValue($this);

        $this->location = GenericCodeLocation::createFromNode($node);
    }

    public function symbol(): string
    {
        return $this->symbol;
    }

    public function left()
    {
        return $this->left;
    }

    public function right()
    {
        return $this->right;
    }

    public function value()
    {
        return $this->value;
    }
}

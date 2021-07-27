<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Support\Transformer;
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

    public function __construct(BinaryOp $node, CodeLocation $location)
    {
        $this->symbol = $node->getOperatorSigil();
        $this->left = Transformer::parserNodeToResultNode($node->left);
        $this->right = Transformer::parserNodeToResultNode($node->right);

//        $this->value = '';
//        if (property_exists($this->left, 'value') && property_exists($this->right, 'value')) {
        $leftValue = '';
        $rightValue = '';

        $this->value = Transformer::binaryOperationNodeToValue($this);

        $this->location = $location;
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

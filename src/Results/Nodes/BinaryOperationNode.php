<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Support\Transformer;
use PhpParser\Node\Expr\BinaryOp;

class BinaryOperationNode implements OperationNode
{
    /** @var string */
    public $symbol;

    /** @var mixed|ResultNode|ValueNode */
    public $left;

    /** @var mixed|ResultNode|ValueNode */
    public $right;

    public function __construct(BinaryOp $node)
    {
        $this->symbol = $node->getOperatorSigil();
        $this->left = Transformer::parserNodeToResultNode($node->left);
        $this->right = Transformer::parserNodeToResultNode($node->right);
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
}

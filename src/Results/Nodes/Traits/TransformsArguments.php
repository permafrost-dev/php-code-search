<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Traits;

use Permafrost\PhpCodeSearch\Results\Nodes\FunctionCallNode;
use Permafrost\PhpCodeSearch\Results\Nodes\Scalar\NumberNode;
use Permafrost\PhpCodeSearch\Results\Nodes\Scalar\StringNode;
use Permafrost\PhpCodeSearch\Results\Nodes\StaticMethodCallNode;
use Permafrost\PhpCodeSearch\Results\Nodes\VariableNode;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;

trait TransformsArguments
{
    public function transformArgumentsToNodes(array $args)
    {
        $nodes = [];

        foreach($args as $arg) {
            $nodes[] = $this->transformArgumentToResultNode($arg);
        }

        return $nodes;
    }

    public function transformArgumentToResultNode(Arg $arg)
    {
        if ($arg instanceof String_) {
            return new StringNode($arg->value);
        }

        if ($arg instanceof LNumber || $arg instanceof DNumber) {
            return new NumberNode($arg->value);
        }

        if ($arg instanceof Variable) {
            return VariableNode::create($arg->name);
        }

        if ($arg instanceof FuncCall) {
            return FunctionCallNode::create($arg->name, $arg->args);
        }

        if ($arg instanceof StaticCall) {
            return StaticMethodCallNode::create($arg->class->toString(), $arg->name->toString());
        }

        return $arg;
    }
}

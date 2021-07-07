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
use PhpParser\Node\Scalar;
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

    protected function transformArgumentToResultNode(Arg $arg)
    {
        if ($arg->value instanceof String_) {
            return new StringNode($arg->value->value);
        }

        if ($arg->value instanceof LNumber || $arg->value instanceof DNumber) {
            return new NumberNode($arg->value->value);
        }

        if ($arg->value instanceof Variable) {
            return VariableNode::create($arg->value->name);
        }

        if ($arg->value instanceof FuncCall) {
            return FunctionCallNode::create($arg->value->name, $arg->value->args);
        }

        if ($arg->value instanceof StaticCall) {
            return StaticMethodCallNode::create($arg->value->class->toString(), $arg->value->name->toString(), $arg->value->args);
        }

        return $arg;
    }
}

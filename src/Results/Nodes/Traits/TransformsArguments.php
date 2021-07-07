<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Traits;

use Permafrost\PhpCodeSearch\Results\Nodes\ArrayNode;
use Permafrost\PhpCodeSearch\Results\Nodes\FunctionCallNode;
use Permafrost\PhpCodeSearch\Results\Nodes\MethodCallNode;
use Permafrost\PhpCodeSearch\Results\Nodes\Scalar\NumberNode;
use Permafrost\PhpCodeSearch\Results\Nodes\Scalar\StringNode;
use Permafrost\PhpCodeSearch\Results\Nodes\StaticMethodCallNode;
use Permafrost\PhpCodeSearch\Results\Nodes\VariableNode;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
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

    protected function transformArgumentToResultNode($arg)
    {
        $value = $arg;

        if ($arg instanceof Array_) {
            return $this->transformArgumentsToNodes($value->items);
        }

        if ($arg instanceof Arg) {
            $value = $arg->value;
        }

        if ($arg instanceof ArrayItem) {
            $value = $arg->value;
        }

        if ($value instanceof String_) {
            return new StringNode($value->value);
        }

        if ($value instanceof LNumber || $value instanceof DNumber) {
            return new NumberNode($value->value);
        }

        if ($value instanceof Variable) {
            return VariableNode::create($value->name);
        }

        if ($value instanceof FuncCall) {
            return FunctionCallNode::create($value->name, $value->args);
        }

        if ($value instanceof StaticCall) {
            return StaticMethodCallNode::create($value->class->toString(), $value->name->toString(), $value->args);
        }

        if ($value instanceof MethodCall) {
            return MethodCallNode::create($value->var->name, $value->name->toString(), $value->args);
        }

        if ($value instanceof Array_) {
            return new ArrayNode($value->items);
        }

        return $arg;
    }
}

<?php

namespace Permafrost\PhpCodeSearch\Support;

use Permafrost\PhpCodeSearch\Results\Nodes\ArrayItemNode;
use Permafrost\PhpCodeSearch\Results\Nodes\ArrayNode;
use Permafrost\PhpCodeSearch\Results\Nodes\AssignmentNode;
use Permafrost\PhpCodeSearch\Results\Nodes\AssignmentOperationNode;
use Permafrost\PhpCodeSearch\Results\Nodes\BinaryOperationNode;
use Permafrost\PhpCodeSearch\Results\Nodes\FunctionCallNode;
use Permafrost\PhpCodeSearch\Results\Nodes\MethodCallNode;
use Permafrost\PhpCodeSearch\Results\Nodes\PropertyAccessNode;
use Permafrost\PhpCodeSearch\Results\Nodes\Scalar\NumberNode;
use Permafrost\PhpCodeSearch\Results\Nodes\Scalar\StringNode;
use Permafrost\PhpCodeSearch\Results\Nodes\StaticMethodCallNode;
use Permafrost\PhpCodeSearch\Results\Nodes\VariableNode;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;

class Transformer
{
    public static function parserNodesToResultNodes(array $nodes): array
    {
        $result = [];

        foreach ($nodes as $node) {
            $result[] = static::parserNodeToResultNode($node);
        }

        return $result;
    }

    public static function parserNodeToResultNode($node)
    {
        $value = $node;

        if ($node instanceof Array_) {
            return static::parserNodesToResultNodes($value->items);
        }

        if ($node instanceof Arg) {
            $value = $node->value;
        }

        if ($node instanceof ArrayItem) {
            $value = $node->value;
            $key = $node->key;

            return new ArrayItemNode($key, $value);
        }

        if ($value instanceof String_) {
            return new StringNode($value->value);
        }

        if ($value instanceof LNumber || $value instanceof DNumber) {
            return new NumberNode($value->value);
        }

        if ($value instanceof Array_) {
            return new ArrayNode($value->items);
        }

        if ($value instanceof Variable) {
            return VariableNode::create($value->name);
        }

        if ($value instanceof Assign) {
            return AssignmentNode::create($value->var->name, $value->expr);
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

        if ($value instanceof PropertyFetch) {
            return new PropertyAccessNode($value->var->name, $value->name->toString());
        }

        if ($value instanceof BinaryOp) {
            return new BinaryOperationNode($value);
        }

        if ($value instanceof AssignOp) {
            return new AssignmentOperationNode($value);
        }

        return $node;
    }
}

<?php

namespace Permafrost\PhpCodeSearch\Support;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
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

        $location = GenericCodeLocation::create(
            $node ? $node->getStartLine() : -1,
            $node ? $node->getEndLine() : -1
        );

        if ($node instanceof Array_) {
            return static::parserNodesToResultNodes($value->items);
        }

        if ($node instanceof Arg) {
            $value = $node->value;
        }

        if ($node instanceof ArrayItem) {
            $value = $node->value;
            $key = $node->key;

            return new ArrayItemNode($key, $value, $location);
        }

        if ($value instanceof String_) {
            return new StringNode($value->value, $location);
        }

        if ($value instanceof LNumber || $value instanceof DNumber) {
            return new NumberNode($value->value, $location);
        }

        if ($value instanceof Array_) {
            return new ArrayNode($value->items, $location);
        }

        if ($value instanceof Variable) {
            return VariableNode::create($value);
        }

        if ($value instanceof Assign) {
            return AssignmentNode::create($value);
        }

        if ($value instanceof FuncCall) {
            return FunctionCallNode::create($value);
        }

        if ($value instanceof StaticCall) {
            return StaticMethodCallNode::create($value);
        }

        if ($value instanceof MethodCall) {
            return MethodCallNode::create($value);
        }

        if ($value instanceof PropertyFetch) {
            return new PropertyAccessNode($value->var->name, $value->name->toString(), $location);
        }

        if ($value instanceof BinaryOp) {
            return new BinaryOperationNode($value, $location);
        }

        if ($value instanceof AssignOp) {
            return new AssignmentOperationNode($value, $location);
        }

        return $node;
    }

    public static function binaryOperationNodeToValue(BinaryOperationNode $node)
    {
        $nodeMap = [
            'left' => '',
            'right' => '',
        ];

        foreach ($nodeMap as $name => &$value) {
            $sideNode = $node->$name;

            if ($sideNode instanceof BinaryOperationNode) {
                $value = static::binaryOperationNodeToValue($sideNode);
            }

            if ($sideNode instanceof NumberNode) {
                $value = $sideNode->value;
            }

            if ($sideNode instanceof StringNode) {
                $value = str_replace("'", "\\'", $sideNode->value);
                $value = "'{$value}'";
            }

            if ($sideNode instanceof VariableNode) {
                $value = '$' . $sideNode->name;
            }
        }

        return $nodeMap['left'] . ' ' . $node->symbol() . ' ' . $nodeMap['right'];
    }
}

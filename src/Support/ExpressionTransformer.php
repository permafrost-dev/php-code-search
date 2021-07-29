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
use Permafrost\PhpCodeSearch\Results\Nodes\StaticPropertyAccessNode;
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
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;

class ExpressionTransformer
{
    public static function parserNodesToResultNodes(array $nodes): array
    {
        return collect($nodes)->map(function($node) {
            return static::parserNodeToResultNode($node);
        })->all();
    }

    public static function parserNodeToResultNode($node)
    {
        $value = $node;

        $nodeMap = [
            Array_::class => ArrayNode::class,
            //ArrayItem::class => ArrayItemNode::class,
            Assign::class => AssignmentNode::class,
            AssignOp::class => AssignmentOperationNode::class,
            BinaryOp::class => BinaryOperationNode::class,
            DNumber::class => NumberNode::class,
            FuncCall::class => FunctionCallNode::class,
            LNumber::class => NumberNode::class,
            MethodCall::class => MethodCallNode::class,
            PropertyFetch::class => PropertyAccessNode::class,
            StaticCall::class => StaticMethodCallNode::class,
            StaticPropertyFetch::class => StaticPropertyAccessNode::class,
            String_::class => StringNode::class,
            Variable::class => VariableNode::class,
        ];

        if ($node instanceof Array_) {
            return static::parserNodesToResultNodes($value->items);
        }

        if ($node instanceof ArrayItem) {
            return new ArrayItemNode($node);
        }

        if ($node instanceof Arg) {
            $value = $node->value;
        }

        foreach($nodeMap as $parserNodeClass => $resultNodeClass) {
            if ($value instanceof $parserNodeClass) {
                return new $resultNodeClass($value);
            }
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

<?php

namespace Permafrost\PhpCodeSearch\Visitors;

use Permafrost\PhpCodeSearch\Code\FunctionCallLocation;
use Permafrost\PhpCodeSearch\Code\StaticMethodCallLocation;
use Permafrost\PhpCodeSearch\Results\FileSearchResults;
use Permafrost\PhpCodeSearch\Results\Nodes\FunctionCallNode;
use Permafrost\PhpCodeSearch\Results\Nodes\StaticMethodCallNode;
use Permafrost\PhpCodeSearch\Results\Nodes\VariableNode;
use Permafrost\PhpCodeSearch\Support\Arr;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\NodeVisitorAbstract;

class FunctionCallVisitor extends NodeVisitorAbstract
{
    /** @var FileSearchResults */
    protected $results;

    protected $functionNames = [];

    protected $variableNames = [];

    public function __construct(FileSearchResults $results, array $functionNames, array $variableNames)
    {
        $this->results = $results;
        $this->functionNames = $functionNames;
        $this->variableNames = $variableNames;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof FuncCall) {
            if (Arr::matches($node->name, $this->functionNames, true)) {
                $resultNode = FunctionCallNode::create($node->name->toString());

                $location = FunctionCallLocation::create(
                    $node->name->parts[0],
                    $node->getStartLine(),
                    $node->getEndLine()
                );

                $this->results->add($resultNode, $location);
            }
        }

        if ($node instanceof Node\Expr\StaticCall) {
            $resultNode = StaticMethodCallNode::create($node->class->toString(), $node->name->toString());

            $location = StaticMethodCallLocation::create(
                $node->class->parts[0],
                $node->name->toString(),
                $node->getStartLine(),
                $node->getEndLine()
            );

            $this->results->add($resultNode, $location);
        }

        if ($node instanceof Node\Expr\MethodCall) {
            $resultNode = FunctionCallNode::create($node->name->toString());

            $location = FunctionCallLocation::create(
                $node->name->toString(),
                $node->getStartLine(),
                $node->getEndLine()
            );

            $this->results->add($resultNode, $location);
        }

        if ($node instanceof Node\Expr\Variable) {
            if (Arr::matches($node->name, $this->variableNames, true)) {
                $resultNode = VariableNode::create($node->name);

                $location = FunctionCallLocation::create(
                    $node->name,
                    $node->getStartLine(),
                    $node->getEndLine()
                );

                $this->results->add($resultNode, $location);
            }
        }

        if ($node instanceof Node\Expr\New_) {
            $resultNode = VariableNode::create($node->class->toString());

            $location = FunctionCallLocation::create(
                $node->class->parts[0],
                $node->getStartLine(),
                $node->getEndLine()
            );

            $this->results->add($resultNode, $location);
        }

        if ($node instanceof Node\Expr\Assign) {
//            print_r($node->expr->getSubNodeNames());
//            print_r($node->var->getSubNodeNames());
//            print_r([$node->var->name]);
            $resultNode = FunctionCallNode::create($node->var->name);

            $location = FunctionCallLocation::create(
                $node->var->name,
                $node->getStartLine(),
                $node->getEndLine()
            );

            $this->results->add($resultNode, $location);
        }
    }
}

<?php

namespace Permafrost\PhpCodeSearch\Visitors;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\FileSearchResults;
use Permafrost\PhpCodeSearch\Results\Nodes\MethodCallNode;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class MethodCallVisitor extends NodeVisitorAbstract
{
    /** @var FileSearchResults */
    protected $results;

    protected $names = [];

    public function __construct(FileSearchResults $results, array $names)
    {
        $this->results = $results;
        $this->names = $names;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\MethodCall) {
            $resultNode = MethodCallNode::create($node->var->name, $node->name->toString(), $node->args);

            $location = GenericCodeLocation::create(
                $node->getStartLine(),
                $node->getEndLine()
            );

            $this->results->add($resultNode, $location);
        }
    }
}

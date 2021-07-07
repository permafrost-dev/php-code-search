<?php

namespace Permafrost\PhpCodeSearch\Visitors;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\FileSearchResults;
use Permafrost\PhpCodeSearch\Results\Nodes\StaticMethodCallNode;
use Permafrost\PhpCodeSearch\Support\Arr;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class StaticCallVisitor extends NodeVisitorAbstract
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
        if ($node instanceof Node\Expr\StaticCall) {
            $name = $node->class->toString();
            $methodName = $node->name->toString();

            if (Arr::matches($name, $this->names, true) || Arr::matches("{$name}::{$methodName}", $this->names, true)) {
                $resultNode = StaticMethodCallNode::create($node->class->toString(), $node->name->toString(), $node->args);

                $location = GenericCodeLocation::create(
                    $node->getStartLine(),
                    $node->getEndLine()
                );

                $this->results->add($resultNode, $location);
            }
        }
    }
}

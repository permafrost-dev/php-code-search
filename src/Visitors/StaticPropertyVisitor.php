<?php

namespace Permafrost\PhpCodeSearch\Visitors;

use Permafrost\PhpCodeSearch\Results\FileSearchResults;
use Permafrost\PhpCodeSearch\Results\Nodes\StaticPropertyAccessNode;
use Permafrost\PhpCodeSearch\Support\Arr;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class StaticPropertyVisitor extends NodeVisitorAbstract
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
        if ($node instanceof Node\Expr\StaticPropertyFetch) {
            $name = $node->class->toString();
            $methodName = $node->name->toString();

            if (Arr::matches($methodName, $this->names, true) || Arr::matches("{$name}::\${$methodName}", $this->names, true)) {
                $resultNode = new StaticPropertyAccessNode($node);

                $this->results->add($resultNode, $resultNode->location());
            }
        }
    }
}

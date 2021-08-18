<?php

namespace Permafrost\PhpCodeSearch\Visitors;

use Permafrost\PhpCodeSearch\Results\FileSearchResults;
use Permafrost\PhpCodeSearch\Results\Nodes\VariableNode;
use Permafrost\PhpCodeSearch\Support\Arr;
use Permafrost\PhpCodeSearch\Support\NameResolver;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class NewClassVisitor extends NodeVisitorAbstract
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
        if ($node instanceof Node\Expr\New_) {
            if (Arr::matches(NameResolver::resolve($node->class), $this->names, true)) {
                $resultNode = VariableNode::create($node);

                $this->results->add($resultNode, $resultNode->location());
            }
        }
    }
}

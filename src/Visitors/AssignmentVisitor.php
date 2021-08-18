<?php


namespace Permafrost\PhpCodeSearch\Visitors;

use Permafrost\PhpCodeSearch\Results\FileSearchResults;
use Permafrost\PhpCodeSearch\Results\Nodes\AssignmentNode;
use Permafrost\PhpCodeSearch\Support\Arr;
use Permafrost\PhpCodeSearch\Support\NameResolver;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class AssignmentVisitor extends NodeVisitorAbstract
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
        if ($node instanceof Node\Expr\Assign) {
            if (! $node->var instanceof Node\Expr\ArrayDimFetch) {
                $name = NameResolver::resolve($node->var);

                if (is_array($name)) {
                    $name = array_pop($name);
                }

                if (Arr::matches($name ?? '', $this->names, true)) {
                    $resultNode = AssignmentNode::create($node);

                    $this->results->add($resultNode, $resultNode->location());
                }
            }
        }
    }
}

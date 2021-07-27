<?php


namespace Permafrost\PhpCodeSearch\Visitors;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\FileSearchResults;
use Permafrost\PhpCodeSearch\Results\Nodes\AssignmentNode;
use Permafrost\PhpCodeSearch\Support\Arr;
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
            if (Arr::matches($node->var->name, $this->names, true)) {
                $location = GenericCodeLocation::create(
                    $node->getStartLine(),
                    $node->getEndLine()
                );

                $resultNode = AssignmentNode::create($node->var->name, $node->expr, $location);

                $this->results->add($resultNode, $location);
            }
        }
    }
}

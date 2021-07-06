<?php


namespace Permafrost\PhpCodeSearch\Visitors;


use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\FileSearchResults;
use Permafrost\PhpCodeSearch\Results\Nodes\VariableNode;
use Permafrost\PhpCodeSearch\Support\Arr;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class VariableCallVisitor extends NodeVisitorAbstract
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
        if ($node instanceof Node\Expr\Variable) {
            if (Arr::matches($node->name, $this->names, true)) {
                $resultNode = VariableNode::create($node->name);

                $location = GenericCodeLocation::create(
                    $node->getStartLine(),
                    $node->getEndLine()
                );

                $this->results->add($resultNode, $location);
            }
        }
    }
}

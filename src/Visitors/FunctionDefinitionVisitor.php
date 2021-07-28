<?php


namespace Permafrost\PhpCodeSearch\Visitors;

use Permafrost\PhpCodeSearch\Results\FileSearchResults;
use Permafrost\PhpCodeSearch\Results\Nodes\FunctionDefinitionNode;
use Permafrost\PhpCodeSearch\Support\Arr;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class FunctionDefinitionVisitor extends NodeVisitorAbstract
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
        if ($node instanceof Node\Stmt\Function_) {
            if (Arr::matches($node->name->toString(), $this->names, true)) {
                $resultNode = FunctionDefinitionNode::create($node);

                $this->results->add($resultNode, $resultNode->location());
            }
        }
    }
}

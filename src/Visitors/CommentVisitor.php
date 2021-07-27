<?php

namespace Permafrost\PhpCodeSearch\Visitors;

use Permafrost\PhpCodeSearch\Results\FileSearchResults;
use Permafrost\PhpCodeSearch\Results\Nodes\CommentNode;
use Permafrost\PhpCodeSearch\Support\Arr;
use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class CommentVisitor extends NodeVisitorAbstract
{
    /** @var FileSearchResults */
    protected $results;

    protected $patterns = [];

    public function __construct(FileSearchResults $results, array $patterns)
    {
        $this->results = $results;
        $this->patterns = $patterns;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Comment) {
            if (Arr::matches($node->getText(), $this->patterns)) {
                $resultNode = CommentNode::create($node);

                $this->results->add($resultNode, $resultNode->location());
            }
        }
    }
}

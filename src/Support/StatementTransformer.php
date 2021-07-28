<?php

namespace Permafrost\PhpCodeSearch\Support;

use Permafrost\PhpCodeSearch\Results\Nodes\ParameterNode;
use PhpParser\Node;

class StatementTransformer
{
    public function parserNodeToResultNode(Node $node)
    {
        if ($node instanceof Node\Param) {
            return new ParameterNode($node);
        }

        return $node;
    }

    public function parserNodesToResultNode(array $nodes)
    {
        $result = [];

        foreach($nodes as $node) {
            $result[] = $this->parserNodeToResultNode($node);
        }

        return $result;
    }
}

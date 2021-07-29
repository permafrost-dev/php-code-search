<?php

namespace Permafrost\PhpCodeSearch\Support;

use Permafrost\PhpCodeSearch\Results\Nodes\ParameterNode;
use PhpParser\Node;

class StatementTransformer
{
    public function parserNodeToResultNode(Node $node)
    {
        $map = [
            Node\Param::class => ParameterNode::class,
        ];

        foreach ($map as $parserNodeClass => $resultNodeClass) {
            if ($node instanceof $parserNodeClass) {
                return new $resultNodeClass($node);
            }
        }

        return $node;
    }

    public function parserNodesToResultNode(array $nodes): array
    {
        return collect($nodes)->map(function ($node) {
            return $this->parserNodeToResultNode($node);
        })->toArray();
    }
}

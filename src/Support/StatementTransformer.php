<?php

namespace Permafrost\PhpCodeSearch\Support;

use Permafrost\PhpCodeSearch\Results\Nodes\ParameterNode;
use PhpParser\Node;

class StatementTransformer
{
    public static function parserNodeToResultNode(Node $node)
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

    public static function parserNodesToResultNode(array $nodes): array
    {
        return collect($nodes)->map(function ($node) {
            return self::parserNodeToResultNode($node);
        })->toArray();
    }
}

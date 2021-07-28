<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Traits;

use Permafrost\PhpCodeSearch\Support\ExpressionTransformer;

trait TransformsArguments
{
    public function transformArgumentsToNodes(array $args): array
    {
        return ExpressionTransformer::parserNodesToResultNodes($args);
    }
}

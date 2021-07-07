<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Traits;

use Permafrost\PhpCodeSearch\Support\Transformer;

trait TransformsArguments
{
    public function transformArgumentsToNodes(array $args): array
    {
        return Transformer::parserNodesToResultNodes($args);
    }
}

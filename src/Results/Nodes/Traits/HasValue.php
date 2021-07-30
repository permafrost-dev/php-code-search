<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Traits;

use Permafrost\PhpCodeSearch\Results\Nodes\Scalar\StringNode;
use Permafrost\PhpCodeSearch\Support\ExpressionTransformer;

trait HasValue
{
    /** @var string|StringNode */
    public $value;

    public function value()
    {
        return $this->value;
    }

    protected function bootHasValue($node): void
    {
        if (property_exists($node, 'value')) {
            $this->value = ExpressionTransformer::parserNodeToResultNode($node->value);
        }
    }
}

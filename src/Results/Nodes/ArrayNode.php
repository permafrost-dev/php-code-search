<?php


namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Results\Nodes\Traits\TransformsArguments;
use PhpParser\Node\Expr\Array_;

class ArrayNode implements ValueNode
{
    use TransformsArguments;

    /** @var array|ValueNode[]|ResultNode[] */
    public $value;

    public function __construct($value)
    {
        if ($value instanceof Array_) {
            $value = $value->items;
        }

        $this->value = $this->transformArgumentsToNodes($value);
    }

    public function value()
    {
        return $this->value;
    }
}

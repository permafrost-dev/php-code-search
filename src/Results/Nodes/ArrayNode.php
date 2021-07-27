<?php


namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\TransformsArguments;
use PhpParser\Node\Expr\Array_;

class ArrayNode implements ValueNode
{
    use HasLocation;
    use TransformsArguments;

    /** @var array|ValueNode[]|ResultNode[] */
    public $value;

    public function __construct($value, CodeLocation $location)
    {
        if ($value instanceof Array_) {
            $value = $value->items;
        }

        $this->value = $this->transformArgumentsToNodes($value);
        $this->location = $location;
    }

    public function value()
    {
        return $this->value;
    }
}

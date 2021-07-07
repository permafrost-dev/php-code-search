<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Scalar;

use PhpParser\Node\Scalar\String_;

class StringNode implements \Permafrost\PhpCodeSearch\Results\Nodes\ValueNode
{
    /** @var string */
    public $value;

    public function __construct($value)
    {
        if ($value instanceof String_) {
            $value = $value->value;
        }

        $this->value = $value;
    }


    public function value()
    {
        return $this->value;
    }
}

<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Scalar;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use PhpParser\Node\Scalar\String_;

class StringNode implements \Permafrost\PhpCodeSearch\Results\Nodes\ValueNode
{
    use HasLocation;

    /** @var string */
    public $value;

    public function __construct($value, CodeLocation $location)
    {
        if ($value instanceof String_) {
            $value = $value->value;
        }

        $this->value = $value;
        $this->location = $location;
    }

    public function value()
    {
        return $this->value;
    }
}

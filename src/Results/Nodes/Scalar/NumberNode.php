<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Scalar;

use Permafrost\PhpCodeSearch\Results\Nodes\ValueNode;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;

class NumberNode implements ValueNode
{
    /** @var int|float */
    public $value;

    public function __construct($value)
    {
        if ($value instanceof LNumber || $value instanceof DNumber) {
            $value = $value->value;
        }

        $this->value = $value;
    }

    public function value()
    {
        return $this->value;
    }
}

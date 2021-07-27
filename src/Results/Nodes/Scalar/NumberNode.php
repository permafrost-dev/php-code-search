<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Scalar;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\ValueNode;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;

class NumberNode implements ValueNode
{
    use HasLocation;

    /** @var int|float */
    public $value;

    public function __construct($value, CodeLocation $location)
    {
        if ($value instanceof LNumber || $value instanceof DNumber) {
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

<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Scalar;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\ValueNode;
use PhpParser\Node;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;

class NumberNode implements ValueNode
{
    use HasLocation;

    /** @var int|float */
    public $value;

    public function __construct(Node $node)
    {
        $value = $node->value;

        if ($value instanceof LNumber || $value instanceof DNumber) {
            $value = $value->value;
        }

        $this->value = $value;
        $this->location = GenericCodeLocation::createFromNode($node);
    }

    public function value()
    {
        return $this->value;
    }
}

<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Scalar;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use PhpParser\Node;
use PhpParser\Node\Scalar\String_;

class StringNode implements \Permafrost\PhpCodeSearch\Results\Nodes\ValueNode
{
    use HasLocation;

    /** @var string */
    public $value;

    public function __construct(Node $node)
    {
        $value = $node->value;

        if ($value instanceof String_) {
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

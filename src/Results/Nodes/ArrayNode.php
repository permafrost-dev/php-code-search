<?php


namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\TransformsArguments;
use PhpParser\Node;
use PhpParser\Node\Expr\Array_;

class ArrayNode implements ValueNode
{
    use HasLocation;
    use TransformsArguments;

    /** @var array|ValueNode[]|ResultNode[] */
    public $value;

    public function __construct(Node $node)
    {
        $value = $node;

        if ($node instanceof Array_) {
            $value = $node->items;
        }

        $this->value = $this->transformArgumentsToNodes($value);
        $this->location = GenericCodeLocation::createFromNode($node);
    }

    public function value()
    {
        return $this->value;
    }
}

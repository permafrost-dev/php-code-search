<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Support\Transformer;
use PhpParser\Node;

class ArrayItemNode implements ValueNode, ResultNode
{
    use HasLocation;

    /** @var mixed|int|string|null */
    public $key;

    /** @var array|mixed|ResultNode|ValueNode */
    public $value;

    public function __construct(Node $node)
    {
        $this->key = Transformer::parserNodeToResultNode($node->key);
        $this->value = Transformer::parserNodeToResultNode($node->value);
        $this->location = GenericCodeLocation::createFromNode($node);
    }

    public function name(): string
    {
        return $this->key;
    }

    public function value()
    {
        return $this->value;
    }
}

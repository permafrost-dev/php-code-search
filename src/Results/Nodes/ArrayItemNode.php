<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Support\Transformer;

class ArrayItemNode implements ValueNode, ResultNode
{
    /** @var mixed|int|string|null */
    public $key;

    /** @var array|mixed|ResultNode|ValueNode */
    public $value;

    public function __construct($key, $value)
    {
        $this->key = Transformer::parserNodeToResultNode($key);
        $this->value = Transformer::parserNodeToResultNode($value);
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

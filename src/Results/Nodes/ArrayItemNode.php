<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Support\Transformer;

class ArrayItemNode implements ValueNode, ResultNode
{
    use HasLocation;

    /** @var mixed|int|string|null */
    public $key;

    /** @var array|mixed|ResultNode|ValueNode */
    public $value;

    public function __construct($key, $value, CodeLocation $location)
    {
        $this->key = Transformer::parserNodeToResultNode($key);
        $this->value = Transformer::parserNodeToResultNode($value);
        $this->location = $location;
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

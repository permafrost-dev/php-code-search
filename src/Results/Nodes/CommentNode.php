<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

class CommentNode implements ResultNode
{
    /** @var string */
    public $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function create(string $value): self
    {
        return new static(...func_get_args());
    }

    public function name(): string
    {
        return $this->value;
    }

    public function value(): string
    {
        return $this->value;
    }
}

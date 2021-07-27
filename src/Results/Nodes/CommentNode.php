<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;

class CommentNode implements ResultNode
{
    use HasLocation;

    /** @var string */
    public $value;

    public function __construct(string $value, CodeLocation $location)
    {
        $this->value = $value;
        $this->location = $location;
    }

    public static function create(string $value, CodeLocation $location): self
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

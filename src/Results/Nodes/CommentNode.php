<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use PhpParser\Comment;

class CommentNode implements ResultNode
{
    use HasLocation;

    /** @var string */
    public $value;

    public function __construct(Comment $node)
    {
        $this->value = $node->getText();
        $this->location = GenericCodeLocation::createFromNode($node);
    }

    public static function create(Comment $node): self
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

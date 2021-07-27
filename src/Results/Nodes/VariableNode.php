<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use PhpParser\Node;

class VariableNode implements ResultNode
{
    use HasLocation;

    /** @var string */
    public $name;

    public function __construct(Node $node)
    {
        if ($node instanceof Node\Expr\New_) {
            $this->name = $node->class->toString();
        } else {
            $this->name = $node->name;
        }

        $this->location = GenericCodeLocation::createFromNode($node);
    }

    public static function create(Node $node): self
    {
        return new static(...func_get_args());
    }

    public function name(): string
    {
        return $this->name;
    }
}

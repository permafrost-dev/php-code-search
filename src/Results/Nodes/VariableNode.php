<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Support\NameResolver;
use PhpParser\Node;

class VariableNode implements ResultNode
{
    use HasLocation;

    /** @var string */
    public $name;

    public function __construct(Node $node)
    {
        $this->name = NameResolver::resolve($node);
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

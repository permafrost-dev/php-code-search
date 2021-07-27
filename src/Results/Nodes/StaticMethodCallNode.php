<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\TransformsArguments;
use PhpParser\Node;

class StaticMethodCallNode implements ResultNode
{
    use HasLocation;
    use TransformsArguments;

    /** @var string */
    public $className;

    /** @var string */
    public $methodName;

    /** @var string */
    public $name;

    /** @var array|ResultNode[]|ValueNode[] */
    public $args;

    public function __construct(Node\Expr\StaticCall $node)
    {
        $this->className = $node->class->toString();
        $this->methodName = $node->name->toString();
        $this->args = $this->transformArgumentsToNodes($node->args);
        $this->name = $this->name();
        $this->location = GenericCodeLocation::createFromNode($node);
    }

    public static function create(Node\Expr\StaticCall $node): self
    {
        return new static(...func_get_args());
    }

    public function name(): string
    {
        return "{$this->className}::{$this->methodName}";
    }
}

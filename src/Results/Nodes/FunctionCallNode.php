<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\TransformsArguments;
use PhpParser\Node;

class FunctionCallNode implements ResultNode
{
    use HasLocation;
    use TransformsArguments;

    /** @var string */
    public $name;

    /** @var array|ResultNode[]|ValueNode[] */
    public $args;

    public function __construct(Node\Expr\FuncCall $node)
    {
        $this->name = $node->name->toString();
        $this->args = $this->transformArgumentsToNodes($node->args);
        $this->location = GenericCodeLocation::createFromNode($node);
    }

    public static function create(Node\Expr\FuncCall $node): self
    {
        return new static(...func_get_args());
    }

    public function name(): string
    {
        return $this->name;
    }
}

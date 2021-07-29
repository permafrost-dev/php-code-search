<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasName;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\TransformsArguments;
use Permafrost\PhpCodeSearch\Support\StatementTransformer;
use PhpParser\Node;

class FunctionDefinitionNode implements ResultNode
{
    use HasName;
    use HasLocation;
    use TransformsArguments;

    /** @var array|ResultNode[]|ValueNode[] */
    public $args;

    public function __construct(Node\Stmt\Function_ $node)
    {
        $this->name = $node->name->toString();
        $this->args = StatementTransformer::parserNodesToResultNode($node->getParams());
        $this->location = GenericCodeLocation::createFromNode($node);
    }

    public static function create(Node\Stmt\Function_ $node): self
    {
        return new static(...func_get_args());
    }
}

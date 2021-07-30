<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\BootsTraits;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasName;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\TransformsArguments;
use Permafrost\PhpCodeSearch\Support\StatementTransformer;
use PhpParser\Node;

class FunctionDefinitionNode implements ResultNode
{
    use BootsTraits;
    use HasName;
    use HasLocation;
    use TransformsArguments;

    /** @var array|ResultNode[]|ValueNode[] */
    public $args;

    public function __construct(Node\Stmt\Function_ $node)
    {
        $this->bootTraits($node);

        $this->args = StatementTransformer::parserNodesToResultNode($node->getParams());
    }

    public static function create(Node\Stmt\Function_ $node): self
    {
        return new static(...func_get_args());
    }
}

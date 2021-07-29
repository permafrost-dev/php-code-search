<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\BootsTraits;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasName;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\TransformsArguments;
use Permafrost\PhpCodeSearch\Support\ExpressionTransformer;
use PhpParser\Node;

class FunctionCallNode implements ResultNode
{
    use BootsTraits;
    use HasName;
    use HasLocation;

    /** @var array|ResultNode[]|ValueNode[] */
    public $args;

    public function __construct(Node\Expr\FuncCall $node)
    {
        $this->bootTraits($node);

        $this->args = ExpressionTransformer::parserNodesToResultNodes($node->args);
    }

    public static function create(Node\Expr\FuncCall $node): self
    {
        return new static(...func_get_args());
    }
}

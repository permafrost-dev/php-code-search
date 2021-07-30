<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Results\Nodes\Traits\BootsTraits;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasName;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasVisibility;
use Permafrost\PhpCodeSearch\Support\ExpressionTransformer;
use PhpParser\Node;

class ClassPropertyNode implements ResultNode
{
    use BootsTraits;
    use HasName;
    use HasLocation;
    use HasVisibility;

    /** @var ResultNode|ValueNode|null */
    public $default;

    public $isStatic = false;

    public function __construct(Node\Stmt\Property $node)
    {
        $this->bootTraits($node);

        $this->isStatic = $node->isStatic();
        $this->default = ExpressionTransformer::parserNodeToResultNode($node->props[0]->default);
    }
}

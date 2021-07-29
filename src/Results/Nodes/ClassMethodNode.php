<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\BootsTraits;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasName;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasVisibility;
use Permafrost\PhpCodeSearch\Support\NameResolver;
use Permafrost\PhpCodeSearch\Support\StatementTransformer;
use PhpParser\Node;

class ClassMethodNode implements ResultNode
{
    use BootsTraits;
    use HasName;
    use HasLocation;
    use HasVisibility;

    /** @var string|null */
    public $returnType;

    public $isStatic = false;

    public $isAbstract = false;

    /** @var array|ResultNode[]|ValueNode[] */
    public $params = [];

    public function __construct(Node\Stmt\ClassMethod $node)
    {
        $this->bootTraits($node);

        $this->isStatic = $node->isStatic();
        $this->isAbstract = $node->isAbstract();
        $this->returnType = NameResolver::resolve($node->getReturnType());
        $this->params = StatementTransformer::parserNodesToResultNode($node->params);
    }
}

<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Results\Nodes\Traits\BootsTraits;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasName;
use Permafrost\PhpCodeSearch\Support\NameResolver;
use Permafrost\PhpCodeSearch\Support\StatementTransformer;
use PhpParser\Node;

class ClassDefinitionNode implements ResultNode
{
    use BootsTraits;
    use HasName;
    use HasLocation;

    /** @var array|ResultNode[]|ValueNode[] */
    public $properties;

    /** @var array|ResultNode[]|ValueNode[] */
    public $methods;

    public $implements = [];

    public $extends;

    /** @var array|ResultNode[]|ValueNode[] */
    public $constants = [];

    public function __construct(Node\Stmt\Class_ $node)
    {
        $this->bootTraits($node);

        $this->extends = NameResolver::resolve($node->extends);
        $this->implements = NameResolver::resolveAll($node->implements);
        $this->properties = StatementTransformer::parserNodesToResultNode($node->getProperties());
        $this->methods = StatementTransformer::parserNodesToResultNode($node->getMethods());
        $this->constants = StatementTransformer::parserNodesToResultNode($node->getConstants());
    }
}

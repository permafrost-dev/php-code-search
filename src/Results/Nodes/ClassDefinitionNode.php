<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasName;
use Permafrost\PhpCodeSearch\Support\StatementTransformer;
use PhpParser\Node;

class ClassDefinitionNode implements ResultNode
{
    use HasName;
    use HasLocation;

    /** @var array|ResultNode[]|ValueNode[] */
    public $properties;

    /** @var array|ResultNode[]|ValueNode[] */
    public $methods;

    public function __construct(Node\Stmt\Class_ $node)
    {
        $this->name = $node->name->toString();
        $this->properties = StatementTransformer::parserNodesToResultNode($node->getProperties());
        $this->methods = StatementTransformer::parserNodesToResultNode($node->getMethods());
        $this->location = GenericCodeLocation::createFromNode($node);

    }
}

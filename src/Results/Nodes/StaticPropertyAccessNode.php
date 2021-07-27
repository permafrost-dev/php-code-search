<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use PhpParser\Node;

class StaticPropertyAccessNode implements ValueNode, ResultNode
{
    use HasLocation;

    /** @var string */
    public $objectName;

    /** @var string */
    public $propertyName;

    public function __construct(Node\Expr\StaticPropertyFetch $node)
    {
        $this->objectName = $node->class->toString();
        $this->propertyName = $node->name->toString();
        $this->location = GenericCodeLocation::createFromNode($node);
    }

    public function name(): string
    {
        return $this->objectName;
    }

    public function value()
    {
        return $this->propertyName;
    }
}

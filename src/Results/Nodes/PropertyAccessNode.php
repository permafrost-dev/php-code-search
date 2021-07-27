<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use PhpParser\Node;

class PropertyAccessNode implements ValueNode, ResultNode
{
    use HasLocation;

    /** @var string */
    public $objectName;

    /** @var string */
    public $propertyName;

    public function __construct(Node $node)
    {
        $this->objectName = $node->var->name;
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

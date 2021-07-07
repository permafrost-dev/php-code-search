<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

class PropertyAccessNode implements ValueNode, ResultNode
{
    /** @var string */
    public $objectName;

    /** @var string */
    public $propertyName;

    public function __construct(string $objectName, string $propertyName)
    {
        $this->objectName = $objectName;
        $this->propertyName = $propertyName;
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

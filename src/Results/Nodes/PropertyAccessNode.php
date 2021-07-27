<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;

class PropertyAccessNode implements ValueNode, ResultNode
{
    use HasLocation;

    /** @var string */
    public $objectName;

    /** @var string */
    public $propertyName;

    public function __construct(string $objectName, string $propertyName, CodeLocation $location)
    {
        $this->objectName = $objectName;
        $this->propertyName = $propertyName;
        $this->location = $location;
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

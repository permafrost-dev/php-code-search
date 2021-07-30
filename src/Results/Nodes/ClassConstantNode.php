<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Results\Nodes\Traits\BootsTraits;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasName;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasValue;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasVisibility;
use PhpParser\Node;

class ClassConstantNode implements ResultNode
{
    use BootsTraits;
    use HasLocation;
    use HasName;
    use HasValue;
    use HasVisibility;

    public function __construct(Node\Stmt\ClassConst $node)
    {
        $this->bootTraits($node);
        $this->bootHasValue($node->consts[0]);
    }
}

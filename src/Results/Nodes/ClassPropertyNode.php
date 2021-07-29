<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\Traits\HasName;
use Permafrost\PhpCodeSearch\Support\ExpressionTransformer;
use PhpParser\Node;

class ClassPropertyNode implements ResultNode
{
    use HasName;
    use HasLocation;

    /** @var ResultNode|ValueNode|null */
    public $default;

    /** @var string */
    public $visibility = 'unknown';

    public $isStatic = false;

    public function __construct(Node\Stmt\Property $node)
    {
        $visibilityMap = [
            'isPublic' => 'public',
            'isPrivate' => 'private',
            'isProtected' => 'protected',
        ];

        $this->name = $node->props[0]->name;
        $this->default = ExpressionTransformer::parserNodeToResultNode($node->props[0]->default);
        $this->location = GenericCodeLocation::createFromNode($node);
        $this->isStatic = $node->isStatic();

        foreach($visibilityMap as $method => $visibility) {
            if ($node->$method()) {
                $this->visibility = $visibility;
                break;
            }
        }
    }
}

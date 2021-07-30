<?php

namespace Permafrost\PhpCodeSearch\Visitors;

use Permafrost\PhpCodeSearch\Results\Nodes\ClassDefinitionNode;
use Permafrost\PhpCodeSearch\Support\Arr;
use PhpParser\Node;

class ClassDefinitionVisitor extends NodeVisitor
{
    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Class_) {
            if (Arr::matches($node->name->toString(), $this->names, true)) {
                $resultNode = new ClassDefinitionNode($node);

                $this->results->add($resultNode, $resultNode->location());
            }
        }
    }
}

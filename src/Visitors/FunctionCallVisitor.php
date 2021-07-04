<?php

namespace Permafrost\PhpCodeSearch\Visitors;

use Permafrost\PhpCodeSearch\Code\FunctionCallLocation;
use Permafrost\PhpCodeSearch\Results\FileSearchResults;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\NodeVisitorAbstract;

class FunctionCallVisitor extends NodeVisitorAbstract
{
    /** @var FileSearchResults $results */
    protected $results;

    public function __construct(FileSearchResults $results)
    {
        $this->results = $results;
    }

    public function enterNode(Node $node) {
        if ($node instanceof FuncCall) {
            $location = FunctionCallLocation::create(
                $node->name->parts[0],
                $node->getStartLine(),
                $node->getEndLine()
            );

            $this->results->addLocation($location);
        }

        if ($node instanceof Node\Expr\StaticCall) {
            $location = FunctionCallLocation::create(
                $node->class->parts[0],
                $node->getStartLine(),
                $node->getEndLine()
            );

            $this->results->addLocation($location);
        }

        if ($node instanceof Node\Expr\New_) {
            $location = FunctionCallLocation::create(
                $node->class->parts[0],
                $node->getStartLine(),
                $node->getEndLine()
            );

            $this->results->addLocation($location);
        }

        if ($node instanceof Node\Expr\Assign) {
//            print_r($node->expr->getSubNodeNames());
//            print_r($node->var->getSubNodeNames());
//            print_r([$node->var->name]);

            $location = FunctionCallLocation::create(
                $node->var->name,
                $node->getStartLine(),
                $node->getEndLine()
            );

            $this->results->addLocation($location);
        }
    }
}

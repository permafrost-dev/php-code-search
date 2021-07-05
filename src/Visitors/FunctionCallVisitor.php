<?php

namespace Permafrost\PhpCodeSearch\Visitors;

use Permafrost\PhpCodeSearch\Code\FunctionCallLocation;
use Permafrost\PhpCodeSearch\Results\FileSearchResults;
use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\NodeVisitorAbstract;

class FunctionCallVisitor extends NodeVisitorAbstract
{
    /** @var FileSearchResults */
    protected $results;

    protected $functionNames = [];

    public function __construct(FileSearchResults $results, array $functionNames)
    {
        $this->results = $results;
        $this->functionNames = $functionNames;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof FuncCall) {
            if (in_array($node->name->parts[0], $this->functionNames, true)) {
                $location = FunctionCallLocation::create(
                    $node->name->parts[0],
                    $node->getStartLine(),
                    $node->getEndLine()
                );

                $this->results->addLocation($location);
            }
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

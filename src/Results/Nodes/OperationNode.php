<?php


namespace Permafrost\PhpCodeSearch\Results\Nodes;


interface OperationNode
{
    public function symbol(): string;

    /** @var mixed|ResultNode|ValueNode|OperationNode */
    public function left();

    /** @var mixed|ResultNode|ValueNode|OperationNode */
    public function right();
}

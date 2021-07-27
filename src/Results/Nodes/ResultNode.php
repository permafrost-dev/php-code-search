<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes;

use Permafrost\PhpCodeSearch\Code\CodeLocation;

interface ResultNode
{
    public function name(): string;

    public function location(): CodeLocation;
}

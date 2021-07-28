<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Traits;

trait HasName
{
    /** @var string */
    public $name;

    public function name(): string
    {
        return $this->name;
    }
}

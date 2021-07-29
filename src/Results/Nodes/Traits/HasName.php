<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Traits;

use Permafrost\PhpCodeSearch\Support\NameResolver;

trait HasName
{
    /** @var string */
    public $name;

    public function name(): string
    {
        return $this->name;
    }

    protected function bootHasName($node): void
    {
        $this->name = NameResolver::resolve($node);
    }
}

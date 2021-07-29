<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Traits;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;

trait HasLocation
{
    /** @var CodeLocation */
    public $location;

    public function location(): CodeLocation
    {
        return $this->location;
    }

    public function withLocation(CodeLocation $location): self
    {
        $this->location = $location;

        return $this;
    }

    protected function bootHasLocation($node): void
    {
        $this->location = GenericCodeLocation::createFromNode($node);
    }
}

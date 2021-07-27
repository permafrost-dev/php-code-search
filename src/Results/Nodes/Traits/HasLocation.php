<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Traits;

use Permafrost\PhpCodeSearch\Code\CodeLocation;

trait HasLocation
{
    /** @var CodeLocation */
    public $location;

    public function location(): CodeLocation
    {
        return $this->location;
    }
}

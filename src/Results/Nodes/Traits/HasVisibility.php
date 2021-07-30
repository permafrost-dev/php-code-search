<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Traits;

trait HasVisibility
{

    /** @var string */
    public $visibility = 'unknown';

    protected function bootHasVisibility($node): void
    {
        $this->initVisibilityAttribute($node);
    }

    protected function initVisibilityAttribute($node): void
    {
        $visibilityMap = [
            'isPublic' => 'public',
            'isPrivate' => 'private',
            'isProtected' => 'protected',
        ];

        foreach ($visibilityMap as $method => $visibility) {
            if ($node->$method()) {
                $this->visibility = $visibility;

                return;
            }
        }

        $this->visibility = 'unknown';
    }
}

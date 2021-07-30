<?php

namespace Permafrost\PhpCodeSearch\Results\Nodes\Traits;

use Permafrost\PhpCodeSearch\Support\Arr;

trait BootsTraits
{
    protected function bootTraits($node): void
    {
        $reflectionObject = new \ReflectionObject($this);

        collect($reflectionObject->getTraitNames())
            ->map(function(string $name){
                if (strpos($name, __NAMESPACE__) === false) {
                    return null;
                }
                return Arr::last(explode('\\', $name));
            })
            ->filter()
            ->each(function(string $name) use ($node) {
                $bootMethodName = 'boot'.$name;

                if (method_exists($this, $bootMethodName)) {
                    $this->$bootMethodName($node);
                }
            });
    }
}

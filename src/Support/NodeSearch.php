<?php

namespace Permafrost\PhpCodeSearch\Support;

use PhpParser\Node\Expr\StaticCall;

class NodeSearch
{
    public static function containsStaticCallName(StaticCall $node, array $names): bool
    {
        foreach ($names as $name) {
            $nodeName = $node->class->toString();

            if (strpos($name, '::') !== false) {
                $nodeName = $node->class->toString() . '::' . $node->name->toString();
            }

            if ($nodeName === $name) {
                return true;
            }
        }

        return false;
    }
}

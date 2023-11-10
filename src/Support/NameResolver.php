<?php

namespace Permafrost\PhpCodeSearch\Support;

use PhpParser\Node;

class NameResolver
{
    public static function resolve($node)
    {
        if (! $node) {
            return null;
        }

        if (is_string($node)) {
            return $node;
        }

        if (method_exists($node, 'toString')) {
            return $node->toString();
        }

        if ($node instanceof Node\Stmt\Property) {
            return $node->props[0]->name;
        }

        if ($node instanceof Node\Stmt\ClassConst) {
            return $node->consts[0]->name;
        }

        if (self::propertiesExist($node, ['class', 'name'])) {
            $class = static::resolve($node->class);
            $name = static::resolve($node->name);

            if ($node instanceof Node\Expr\MethodCall) {
                return $name;
            }

            if ($node instanceof Node\Expr\StaticCall) {
                if (is_array($class)) {
                    $class = $name;
                }

                return [$class, "{$class}::{$name}"];
            }

            if ($node instanceof Node\Expr\StaticPropertyFetch) {
                return [$name, "{$class}::\${$name}"];
            }

            return [$class, $name];
        }

        if (property_exists($node, 'name')) {
            return static::resolve($node->name);
        }

        if (property_exists($node, 'var')) {
            return static::resolve($node->var);
        }

        if (property_exists($node, 'class')) {
            return static::resolve($node->class);
        }

        return null;
    }

    public static function resolveAll(array $nodes): array
    {
        return collection($nodes)->each(function ($node) {
            return self::resolve($node);
        })->filter()->all();
    }

    protected static function propertiesExist($object, array $propertyNames): bool
    {
        foreach ($propertyNames as $propertyName) {
            if (! property_exists($object, $propertyName)) {
                return false;
            }
        }

        return true;
    }
}

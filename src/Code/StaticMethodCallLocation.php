<?php

namespace Permafrost\PhpCodeSearch\Code;

class StaticMethodCallLocation implements CodeLocation
{
    /** @var string */
    public $className;

    /** @var string */
    public $methodName;

    /** @var string */
    public $name;

    /** @var int */
    public $column = 0;

    /** @var int */
    public $endLine = -1;

    /** @var int */
    public $startLine = -1;

    public function __construct(string $className, string $methodName, int $startLine, int $endLine)
    {
        $this->className = $className;
        $this->methodName = $methodName;
        $this->startLine = $startLine;
        $this->endLine = $endLine;
        $this->name = $this->fullName();
    }

    public static function create(string $className, string $methodName, int $startLine, int $endLine): self
    {
        return new static(...func_get_args());
    }

    public function column(): int
    {
        return $this->column;
    }

    public function endLine(): int
    {
        return $this->endLine;
    }

    public function startLine(): int
    {
        return $this->startLine;
    }

    public function fullName()
    {
        return "{$this->className}::{$this->methodName}";
    }
}

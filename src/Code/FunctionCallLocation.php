<?php

namespace Permafrost\PhpCodeSearch\Code;

class FunctionCallLocation implements CodeLocation
{
    /** @var string $name */
    public $name;

    /** @var int */
    public $startLine = -1;

    /** @var int */
    public $endLine = -1;

    /** @var int */
    public $column = 0;

    public function __construct(string $name, int $startLine, int $endLine)
    {
        $this->name = $name;
        $this->startLine = $startLine;
        $this->endLine = $endLine;
    }

    public static function create(string $name, int $startLine, int $endLine): self
    {
        return new static($name, $startLine, $endLine);
    }

    public function startLine(): int
    {
        return $this->startLine;
    }

    public function endLine(): int
    {
        return $this->endLine;
    }

    public function column(): int
    {
        return $this->column;
    }
}

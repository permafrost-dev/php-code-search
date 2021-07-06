<?php

namespace Permafrost\PhpCodeSearch\Code;

class GenericCodeLocation implements CodeLocation
{
    /** @var int */
    public $column = 0;

    /** @var int */
    public $endLine = -1;

    /** @var int */
    public $startLine = -1;

    public function __construct(int $startLine, int $endLine)
    {
        $this->startLine = $startLine;
        $this->endLine = $endLine;
    }

    public static function create(int $startLine, int $endLine): self
    {
        return new static($startLine, $endLine);
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
}

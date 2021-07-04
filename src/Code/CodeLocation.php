<?php

namespace Permafrost\PhpCodeSearch\Code;

interface CodeLocation
{
    public function startLine(): int;

    public function endLine(): int;

    public function column(): int;

//    /** @var string | null */
//    public $filename = null;
//
//    /** @var int */
//    public $startLine = -1;
//
//    /** @var int */
//    public $endLine = -1;
//
//    public function __construct(string $filename, int $startLine, int $endLine)
//    {
//        $this->filename = $filename;
//        $this->startLine = $startLine;
//        $this->endLine = $endLine;
//    }
}

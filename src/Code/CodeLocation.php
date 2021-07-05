<?php

namespace Permafrost\PhpCodeSearch\Code;

interface CodeLocation
{
    public function column(): int;

    public function endLine(): int;

    public function startLine(): int;
}

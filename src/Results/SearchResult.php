<?php

namespace Permafrost\PhpCodeSearch\Results;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Code\CodeSnippet;
use Permafrost\PhpCodeSearch\Code\FunctionCallLocation;

class SearchResult
{
    /** @var CodeLocation|FunctionCallLocation */
    public $location;

    /** @var CodeSnippet|null */
    public $snippet;

    public function __construct(CodeLocation $location, ?CodeSnippet $snippet)
    {
        $this->location = $location;
        $this->snippet = $snippet;
    }
}

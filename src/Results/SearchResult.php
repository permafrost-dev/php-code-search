<?php

namespace Permafrost\PhpCodeSearch\Results;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Code\CodeSnippet;
use Permafrost\PhpCodeSearch\Code\FunctionCallLocation;
use Permafrost\PhpCodeSearch\Code\StaticMethodCallLocation;
use Permafrost\PhpCodeSearch\Support\File;

class SearchResult
{
    /** @var CodeLocation|FunctionCallLocation|StaticMethodCallLocation */
    public $location;

    /** @var CodeSnippet|null */
    public $snippet;

    /** @var File */
    protected $file;

    /**
     * @param CodeLocation $location
     * @param CodeSnippet|null $snippet
     * @param File|string $file
     */
    public function __construct(CodeLocation $location, ?CodeSnippet $snippet, $file)
    {
        $this->location = $location;
        $this->snippet = $snippet;
        $this->file = is_string($file) ? new File($file) : $file;
    }

    public function file(): File
    {
        return $this->file;
    }
}

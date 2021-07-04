<?php

namespace Permafrost\PhpCodeSearch\Results;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Code\CodeSnippet;
use Permafrost\PhpCodeSearch\Support\File;

class FileSearchResults
{
    /** @var array|SearchResult[] */
    public $results = [];

    /** @var array|SearchError[] */
    public $errors = [];

    /** @var File */
    public $file;

    /** @var bool */
    protected $withSnippets = true;

    public function __construct(File $file, bool $withSnippets = true)
    {
        $this->file = $file;
        $this->withSnippets = $withSnippets;
    }

    public function addLocation(CodeLocation $location): self
    {
        $snippet = $this->makeSnippet($location->startLine());

        $this->results[] = new SearchResult($location, $snippet);

        return $this;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function addError(SearchError $errorResult): self
    {
        $this->errors[] = $errorResult;

        return $this;
    }

    protected function makeSnippet(int $startLine, int $lineCount = 8): ?CodeSnippet
    {
        if (! $this->withSnippets) {
            return null;
        }

        return (new CodeSnippet())
            ->surroundingLine($startLine)
            ->snippetLineCount($lineCount)
            ->fromFile($this->file);
    }
}

<?php

namespace Permafrost\PhpCodeSearch\Results;

use Permafrost\CodeSnippets\CodeSnippet;
use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Support\File;
use Permafrost\PhpCodeSearch\Results\Nodes\ResultNode;

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

    /**
     * @param \Permafrost\PhpCodeSearch\Support\File|\Permafrost\CodeSnippets\File $file
     * @param bool $withSnippets
     */
    public function __construct($file, bool $withSnippets = true)
    {
        $this->file = $file;
        $this->withSnippets = $withSnippets;
    }

    public function add(ResultNode $resultNode, CodeLocation $location): self
    {
        $snippet = $this->makeSnippet($location->startLine());

        $this->results[] = new SearchResult($resultNode, $location, $snippet, $this->file);

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
            ->fromFile($this->file->getRealPath());
    }
}

<?php

namespace Permafrost\PhpCodeSearch\Results;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\CodeSnippets\CodeSnippet;
use Permafrost\CodeSnippets\File;
use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\FunctionCallNode;
use Permafrost\PhpCodeSearch\Results\Nodes\ResultNode;
use Permafrost\PhpCodeSearch\Results\Nodes\StaticMethodCallNode;
use Permafrost\PhpCodeSearch\Results\Nodes\VariableNode;

class SearchResult
{
    /** @var CodeLocation|GenericCodeLocation */
    public $location;

    /** @var ResultNode|FunctionCallNode|StaticMethodCallNode|VariableNode */
    public $node;

    /** @var CodeSnippet|null */
    public $snippet;

    /** @var File */
    protected $file;

    /**
     * @param \Permafrost\PhpCodeSearch\Results\Nodes\ResultNode $node
     * @param \Permafrost\PhpCodeSearch\Code\CodeLocation $location
     * @param \Permafrost\CodeSnippets\CodeSnippet|null $snippet
     * @param \Permafrost\CodeSnippets\File|string $file
     */
    public function __construct(ResultNode $node, CodeLocation $location, ?CodeSnippet $snippet, $file)
    {
        $this->node = $node;
        $this->location = $location;
        $this->snippet = $snippet;
        $this->file = is_string($file) ? new File($file) : $file;
    }

    public function file(): File
    {
        return $this->file;
    }
}

<?php

namespace Permafrost\PhpCodeSearch\Results;

use Permafrost\PhpCodeSearch\Code\CodeLocation;
use Permafrost\PhpCodeSearch\Code\CodeSnippet;
use Permafrost\PhpCodeSearch\Code\FunctionCallLocation;
use Permafrost\PhpCodeSearch\Code\StaticMethodCallLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\FunctionCallNode;
use Permafrost\PhpCodeSearch\Results\Nodes\ResultNode;
use Permafrost\PhpCodeSearch\Results\Nodes\StaticMethodCallNode;
use Permafrost\PhpCodeSearch\Results\Nodes\VariableNode;
use Permafrost\PhpCodeSearch\Support\File;

class SearchResult
{
    /** @var CodeLocation|FunctionCallLocation|StaticMethodCallLocation */
    public $location;

    /** @var ResultNode|FunctionCallNode|StaticMethodCallNode|VariableNode */
    public $node;

    /** @var CodeSnippet|null */
    public $snippet;

    /** @var File */
    protected $file;

    /**
     * @param ResultNode $node
     * @param CodeLocation $location
     * @param CodeSnippet|null $snippet
     * @param File|string $file
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

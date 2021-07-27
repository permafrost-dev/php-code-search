<?php

namespace Permafrost\PhpCodeSearch\Tests\Results;

use Permafrost\CodeSnippets\CodeSnippet;
use Permafrost\CodeSnippets\File;
use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\VariableNode;
use Permafrost\PhpCodeSearch\Results\SearchResult;
use PhpParser\Node\Expr\Variable;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class SearchResultTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function it_creates_the_object_with_correct_properties()
    {
        $file = new File(tests_path('data/file2.txt'));
        $location = new GenericCodeLocation(1, 1);
        $snippet = (new CodeSnippet())->surroundingLine(2)->snippetLineCount(10)->fromFile($file);
        $resultNode = new VariableNode(new Variable('myVar', ['startLine' => 1, 'endLine' => 1]));
        $result = new SearchResult($resultNode, $location, $snippet, $file);

        $this->assertMatchesObjectSnapshot($result);
    }
}

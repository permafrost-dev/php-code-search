<?php

namespace Permafrost\PhpCodeSearch\Tests\Results;

use Permafrost\PhpCodeSearch\Code\CodeSnippet;
use Permafrost\PhpCodeSearch\Code\GenericCodeLocation;
use Permafrost\PhpCodeSearch\Results\Nodes\VariableNode;
use Permafrost\PhpCodeSearch\Results\SearchResult;
use Permafrost\PhpCodeSearch\Support\File;
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
        $snippet = (new CodeSnippet())->fromFile($file);
        $resultNode = new VariableNode('myVar');
        $result = new SearchResult($resultNode, $location, $snippet, $file);

        $this->assertMatchesObjectSnapshot($result);
    }
}

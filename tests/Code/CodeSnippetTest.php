<?php

namespace Permafrost\PhpCodeSearch\Tests\Code;

use Permafrost\PhpCodeSearch\Code\CodeSnippet;
use Permafrost\PhpCodeSearch\Support\File;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class CodeSnippetTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function it_gets_a_snippet_from_a_file()
    {
        $file = new File(tests_path('data/file2.txt'));

        $snippet = (new CodeSnippet())
            ->surroundingLine(3)
            ->snippetLineCount(3)
            ->fromFile($file);

        $this->assertMatchesSnapshot($snippet->getCode());
    }

    /** @test */
    public function it_returns_no_code_when_given_a_file_that_does_not_exist()
    {
        $file = new File(tests_path('data/missing.txt'));

        $snippet = (new CodeSnippet())
            ->surroundingLine(3)
            ->snippetLineCount(3)
            ->fromFile($file);

        $this->assertMatchesSnapshot($snippet->getCode());
    }
}

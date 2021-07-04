<?php

namespace Permafrost\PhpCodeSearch\Tests\Results;

use Permafrost\PhpCodeSearch\Results\FileSearchResults;
use Permafrost\PhpCodeSearch\Results\SearchError;
use Permafrost\PhpCodeSearch\Support\File;
use PHPUnit\Framework\TestCase;

class FileSearchResultsTest extends TestCase
{
    /** @test */
    public function it_returns_false_if_it_has_no_errors()
    {
        $file = new File(tests_path('data/file1.php'));
        $results = new FileSearchResults($file, true);

        $this->assertFalse($results->hasErrors());
    }

    /** @test */
    public function it_returns_true_if_it_has_errors()
    {
        $file = new File(tests_path('data/file1.php'));
        $results = new FileSearchResults($file, true);

        $results->addError(new SearchError(new \Exception('test'), 'test message'));

        $this->assertTrue($results->hasErrors());
        $this->assertCount(1, $results->errors);
    }

}

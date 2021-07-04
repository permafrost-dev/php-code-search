<?php

namespace Permafrost\PhpCodeSearch\Tests;

use Permafrost\PhpCodeSearch\Searcher;
use Permafrost\PhpCodeSearch\Support\File;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class SearcherTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function it_searches_for_function_calls()
    {
        $searcher = new Searcher();
        $file = new File(tests_path('data/file1.php'));

        $results = $searcher
            ->functions(['strtolower', 'strtoupper'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);

        $results = $searcher
            ->functions(['strtoupper', 'printf', 'strtolower'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_searches_for_function_calls_without_snippets()
    {
        $searcher = new Searcher();
        $file = new File(tests_path('data/file1.php'));

        $results = $searcher
            ->withoutSnippets()
            ->functions(['strtolower', 'strtoupper'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_searches_for_static_method_calls()
    {
        $searcher = new Searcher();
        $file = new File(tests_path('data/file1.php'));

        $results = $searcher
            ->static(['MyClass', 'Ray'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_searches_for_var_assignments()
    {
        $searcher = new Searcher();
        $file = new File(tests_path('data/file1.php'));

        $results = $searcher
            ->assignments(['obj'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_searches_for_classes_created_by_new()
    {
        $searcher = new Searcher();
        $file = new File(tests_path('data/file1.php'));

        $results = $searcher
            ->classes(['MyClass'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);
    }
}

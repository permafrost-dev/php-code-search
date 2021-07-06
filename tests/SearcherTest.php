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
    public function it_searches_code_strings()
    {
        $searcher = new Searcher();

        $results = $searcher
            ->functions(['strtolower', 'strtoupper'])
            ->searchCode('<?' . "php \n\$myVar = strtolower('test');\n");

        $this->assertMatchesSnapshot($results->results);
    }

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
    public function it_searches_for_static_method_calls_containing_the_class_and_method_name()
    {
        $searcher = new Searcher();
        $file = new File(tests_path('data/file1.php'));

        $results = $searcher
            ->static(['AnotherClass::enabled'])
            ->search($file);

        $this->assertCount(1, $results->results);
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

    /** @test */
    public function it_only_returns_the_functions_being_searched_for()
    {
        $results = (new Searcher())
            ->functions(['strtolower'])
            ->searchCode('<?' . "php \n\$myVar = strtolower(strtoupper('test'));\n");

        $this->assertCount(1, $results->results);
        $this->assertEquals('strtolower', $results->results[0]->node->name());

        $results = (new Searcher())
            ->functions(['strtoupper'])
            ->searchCode('<?' . "php \n\$myVar = strtolower(strtoupper('test'));\n");

        $this->assertCount(1, $results->results);
        $this->assertEquals('strtoupper', $results->results[0]->node->name());
    }

    /** @test */
    public function it_finds_methods()
    {
        $results = (new Searcher())
            ->methods(['methodOne'])
            ->searchCode('<?' . "php \n\$myVar = \$obj->methodOne('one'); \$obj->methodTwo('two');\n");

        $this->assertCount(1, $results->results);
        $this->assertEquals('$obj->methodOne', $results->results[0]->node->name());
    }

    /** @test */
    public function it_finds_variables()
    {
        $results = (new Searcher())
            ->variables(['obj'])
            ->searchCode('<?' . "php \n\$myVar = \$obj->methodOne('one'); \$obj->methodTwo('two');\n");

        $this->assertCount(2, $results->results);
        $this->assertEquals('obj', $results->results[0]->node->name());
        $this->assertEquals('obj', $results->results[1]->node->name());
    }

    /** @test */
    public function it_finds_variables_using_regex()
    {
        $results = (new Searcher())
            ->variables(['/obj[AB]/'])
            ->searchCode('<?' . "php \n\$objC = \$objA->methodOne('one'); \$objB->methodTwo('two');\n");

        $this->assertCount(2, $results->results);
        $this->assertEquals('objA', $results->results[0]->node->name());
        $this->assertEquals('objB', $results->results[1]->node->name());
    }
}

<?php

namespace Permafrost\PhpCodeSearch\Tests;

use Permafrost\CodeSnippets\File;
use Permafrost\PhpCodeSearch\Searcher;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class SearcherTest extends TestCase
{
    use MatchesSnapshots;

    /** @var Searcher */
    protected $searcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->searcher = $this->getSearcher();
    }

    protected function getSearcher(): Searcher
    {
        return (new Searcher())->withoutSnippets();
    }

    /** @test */
    public function it_searches_code_strings()
    {
        $results = $this->getSearcher()
            ->functions(['strtolower', 'strtoupper'])
            ->searchCode('<?' . "php \n\$myVar = strtolower('test');\n");

        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_searches_for_function_calls()
    {
        $file = new File(tests_path('data/file1.php'));

        $results = $this->getSearcher()
            ->functions(['strtolower', 'strtoupper'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);

        $results = $this->getSearcher()
            ->functions(['strtoupper', 'printf', 'strtolower'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_searches_for_function_calls_without_snippets()
    {
        $file = new File(tests_path('data/file1.php'));

        $results = $this->getSearcher()
            ->functions(['strtolower', 'strtoupper'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_searches_for_multi_line_function_calls()
    {
        $results = $this->getSearcher()
            ->functions(['strtolower', 'strtoupper'])
            ->searchCode('<?' ."php
                \$myStr = strtolower(
                    'test '.
                    'string'
                );
            ");

        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_searches_for_static_method_calls()
    {
        $file = new File(tests_path('data/file1.php'));

        $results = $this->getSearcher()
            ->static(['MyClass', 'Ray'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_searches_for_static_property_accesses()
    {
        $file = new File(tests_path('data/file1.php'));

        $results = $this->getSearcher()
            ->static(['Ray::$someProperty'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_searches_for_static_method_calls_containing_the_class_and_method_name()
    {
        $file = new File(tests_path('data/file1.php'));

        $results = $this->getSearcher()
            ->static(['AnotherClass::enabled'])
            ->search($file);

        $this->assertCount(1, $results->results);
        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_searches_for_var_assignments()
    {
        $file = new File(tests_path('data/file1.php'));

        $results = $this->getSearcher()
            ->assignments(['obj'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_searches_for_classes_created_by_new()
    {
        $file = new File(tests_path('data/file1.php'));

        $results = $this->getSearcher()
            ->classes(['MyClass'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_searches_for_class_definitions()
    {
        $file = new File(tests_path('data/file3.php'));

        $results = $this->getSearcher()
            ->classes(['MyClass1'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_only_returns_the_functions_being_searched_for()
    {
        $results = $this->getSearcher()
            ->functions(['strtolower'])
            ->searchCode('<?' . "php \n\$myVar = strtolower(strtoupper('test'));\n");

        $this->assertCount(1, $results->results);
        $this->assertEquals('strtolower', $results->results[0]->node->name());

        $results = $this->getSearcher()
            ->functions(['strtoupper'])
            ->searchCode('<?' . "php \n\$myVar = strtolower(strtoupper('test'));\n");

        $this->assertCount(1, $results->results);
        $this->assertEquals('strtoupper', $results->results[0]->node->name());
    }

    /** @test */
    public function it_finds_methods()
    {
        $results = $this->getSearcher()
            ->methods(['methodTwo'])
            ->searchCode('<?' . "php \n\$myVar = \$obj->methodOne('one'); \$obj->methodTwo(\$obj->methodOne('two'));\n");

        $this->assertCount(1, $results->results);
        $this->assertEquals('$obj->methodTwo', $results->results[0]->node->name());
    }

    /** @test */
    public function it_transforms_nested_calls_and_arguments()
    {
        $results = $this->getSearcher()
            ->methods(['methodTwo'])
            ->searchCode('<?' . "php \$obj->methodTwo(MyModel::find(1), \$obj->methodOne('two', [2, 3]), [\$this, 'handlerMethod']);\n");

        $this->assertCount(1, $results->results);
        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_finds_complex_assignments()
    {
        $results = $this->getSearcher()
            ->assignments(['myVar2'])
            ->searchCode('<?' . "php
                \$myVar = \$obj->methodTwo(MyModel::find(1), \$obj->methodOne('two', [2, 3]), [\$this, 'handlerMethod']);\n
                \$myVar2 = [1, 2, 3];
                \$myVar2 = [...\$myVar2, \$myVar->someProp, 4, 5, 6];
                \$myVar2 = ['one' => 1, 'two' => \$anotherVar->someMethod()];
            ");

        $this->assertCount(3, $results->results);
        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_finds_binary_operations()
    {
        $results = $this->getSearcher()
            ->assignments(['obj'])
            ->searchCode('<?' . "php
                \$obj = 1 + 3 + 2;
            ");

        $this->assertCount(1, $results->results);
        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_finds_assign_operations()
    {
        $results = $this->getSearcher()
            ->assignments(['obj'])
            ->searchCode('<?' . "php
                \$obj = 'hello ' . 'world';
            ");

        $this->assertCount(1, $results->results);
        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_finds_variables()
    {
        $results = $this->getSearcher()
            ->variables(['obj'])
            ->searchCode('<?' . "php \n\$myVar = \$obj->methodOne('one'); \$obj->methodTwo('two');\n");

        $this->assertCount(2, $results->results);
        $this->assertEquals('obj', $results->results[0]->node->name());
        $this->assertEquals('obj', $results->results[1]->node->name());
    }

    /** @test */
    public function it_finds_variables_using_regex()
    {
        $results = $this->getSearcher()
            ->variables(['/obj[AB]/'])
            ->searchCode('<?' . "php \n\$objC = \$objA->methodOne('one'); \$objB->methodTwo('two');\n");

        $this->assertCount(2, $results->results);
        $this->assertEquals('objA', $results->results[0]->node->name());
        $this->assertEquals('objB', $results->results[1]->node->name());
    }

    /** @test */
    public function it_searches_for_function_definitions()
    {
        $file = new File(tests_path('data/file2.php'));

        $results = $this->getSearcher()
            ->functions(['test2'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);

        $results = $this->getSearcher()
            ->functions(['/test\d+/'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_can_handle_chained_static_and_regular_calls()
    {
        $file = new File(tests_path('data/file4.php'));

        $results = $this->getSearcher()
            ->methods(['firstOrFail'])
            ->search($file);

        $this->assertMatchesSnapshot($results->results);
    }

    /** @test */
    public function it_can_handle_nullable_parameters()
    {
        $results = $this->getSearcher()
            ->functions(['testFive'])
            ->search(tests_path('data/file5.php'));

        $this->assertMatchesSnapshot($results->results);
    }
}

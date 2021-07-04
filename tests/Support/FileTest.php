<?php

namespace Permafrost\PhpCodeSearch\Tests\Support;

use Permafrost\PhpCodeSearch\Support\File;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class FileTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function it_checks_if_a_file_exists()
    {
        $file1 = new File(tests_path('data/file1.php'));
        $file2 = new File(tests_path('data/missing.php'));

        $this->assertTrue($file1->exists());
        $this->assertFalse($file2->exists());
    }

    /** @test */
    public function it_gets_the_real_path_of_a_file()
    {
        $file = new File(tests_path('data/file1.php'));

        $this->assertEquals(realpath(tests_path('data/file1.php')), $file->getRealPath());
    }

    /** @test */
    public function it_counts_the_number_of_lines_in_a_file()
    {
        $file = new File(tests_path('data/file2.txt'));

        $this->assertEquals(5, $file->numberOfLines());
    }

    /** @test */
    public function it_gets_the_first_line_from_a_file()
    {
        $file = new File(tests_path('data/file2.txt'));

        $this->assertEquals('1' . PHP_EOL, $file->getLine());
    }

    /** @test */
    public function it_gets_a_specific_line_from_a_file()
    {
        $file = new File(tests_path('data/file2.txt'));

        $this->assertEquals('2' . PHP_EOL, $file->getLine(2));
    }

    /** @test */
    public function it_gets_the_contents_of_a_file()
    {
        $file = new File(tests_path('data/file2.txt'));

        $this->assertMatchesTextSnapshot($file->contents());
    }
}

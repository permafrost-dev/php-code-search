<?php

namespace Permafrost\PhpCodeSearch\Tests\Support;

use Permafrost\PhpCodeSearch\Support\VirtualFile;
use PHPUnit\Framework\TestCase;

class VirtualFileTest extends TestCase
{
    /** @test */
    public function it_creates_a_temp_file_from_code()
    {
        $file = new VirtualFile(file_get_contents(tests_path('data/file1.php')));

        $this->assertFileExists($file->getRealPath());
        $this->assertFileEquals(tests_path('data/file1.php'), $file->getRealPath());
    }

    /** @test */
    public function it_unlinks_the_temp_file()
    {
        $file = new VirtualFile(file_get_contents(tests_path('data/file1.php')));
        $this->assertFileExists($file->getRealPath());

        $file->unlink();
        $this->assertFileDoesNotExist($file->getRealPath());
    }
}

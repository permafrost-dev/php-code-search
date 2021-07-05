<?php

namespace Permafrost\PhpCodeSearch\Tests\Support;

use Permafrost\PhpCodeSearch\Support\Arr;
use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{
    /** @test */
    public function it_matches_regex_patterns_with_delimiters()
    {
        $this->assertTrue(Arr::matchesAnyPattern('test', ['~ello$~', '~est$~']));
        $this->assertFalse(Arr::matchesAnyPattern('test', ['~ello$~', '~orld$~']));
    }

    /** @test */
    public function it_matches_regex_patterns_without_delimiters()
    {
        $this->assertTrue(Arr::matchesAnyPattern('test', ['ello$', 'est$']));
        $this->assertFalse(Arr::matchesAnyPattern('test', ['ello$', 'orld$']));
    }

    /** @test */
    public function it_matches_exact_strings()
    {
        $this->assertTrue(Arr::matches('test', ['test', 'one']));
        $this->assertFalse(Arr::matches('test', ['TEST', 'one']));
    }

    /** @test */
    public function it_matches_delimited_regex_patterns()
    {
        $this->assertTrue(Arr::matches('test', ['/^te.+$/', 'one']));
        $this->assertFalse(Arr::matches('test', ['/^Test$/', '/^one$/']));
    }
}

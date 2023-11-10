<?php

namespace Permafrost\PhpCodeSearch\Tests\Support;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    /** @test */
    public function it_optionally_allows_chaining_method_calls_when_passed_a_null_argument()
    {
        $this->assertNull(opt(null)->test());
    }

    /** @test */
    public function it_optionally_allows_chaining_method_calls_when_passed_a_valid_class()
    {
        $class = new class {
            public function test()
            {
                return 123;
            }
        };

        $this->assertEquals(123, opt($class)->test());
    }
}

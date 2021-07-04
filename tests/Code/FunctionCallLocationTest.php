<?php

namespace Permafrost\PhpCodeSearch\Tests\Code;

use Permafrost\PhpCodeSearch\Code\FunctionCallLocation;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class FunctionCallLocationTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function it_creates_an_object_with_the_correct_properties()
    {
        $location = new FunctionCallLocation('my_test_func', 1, 3);

        $this->assertMatchesObjectSnapshot($location);
    }

}

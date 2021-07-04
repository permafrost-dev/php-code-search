<?php

namespace Permafrost\PhpCodeSearch\Tests\Results;

use Permafrost\PhpCodeSearch\Results\SearchError;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;

class SearchErrorTest extends TestCase
{
    use MatchesSnapshots;

    /** @test */
    public function it_creates_the_object_with_correct_properties()
    {
        $exception = new \Exception('test message');
        $error = new SearchError($exception, 'test');

        $this->assertMatchesSnapshot($error->message);
    }
}

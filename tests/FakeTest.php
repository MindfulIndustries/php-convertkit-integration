<?php

namespace Tests;

use MindfulIndustries\Integrations\ConvertKit\ConvertKit;

class FakeTest extends TestCase
{
    public function testIsFakeable()
    {
        ConvertKit::fake();
        $this->addToAssertionCount(1);
    }


    public function testSubscriberId()
    {
        $this->assertTrue(
            is_int(ConvertKit::subscriberId($this->faker->safeEmail))
        );

        $this->assertTrue(ConvertKit::assertCalledSubscriberId());
        $this->assertTrue(ConvertKit::assertCalledSubscriberId(1));
    }
}
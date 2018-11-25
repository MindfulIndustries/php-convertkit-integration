<?php

namespace Tests;

use InvalidArgumentException;
use MindfulIndustries\Integrations\ConvertKit\ConvertKit;

class TagsTest extends TestCase
{
    public function testThrowsExceptionOnInvalidArguments()
    {
        ConvertKit::fake(false);

        $this->expectException(InvalidArgumentException::class);
        ConvertKit::tag('jane@example.com', []);

        $this->expectException(InvalidArgumentException::class);
        ConvertKit::tag('jane@exmaple.com', []);

        $this->expectException(InvalidArgumentException::class);
        ConvertKit::untag('jane@exmaple.com', []);
    }


    public function testCanRetrieveTags()
    {
        ConvertKit::fake(false);
        ConvertKit::credentials($_ENV['TEST_CREDENTIALS_KEY'], $_ENV['TEST_CREDENTIALS_SECRET']);

        $this->assertTrue(is_array(ConvertKit::getTags()));
    }


    public function testCanCreateTag()
    {
        ConvertKit::fake(false);
        ConvertKit::credentials($_ENV['TEST_CREDENTIALS_KEY'], $_ENV['TEST_CREDENTIALS_SECRET']);

        $tagId = ConvertKit::createTag(__CLASS__);
        $this->assertTrue(is_int($tagId));
    }


    public function testCanSubscribeEmailToTag()
    {
        ConvertKit::fake(false);
        ConvertKit::credentials($_ENV['TEST_CREDENTIALS_KEY'], $_ENV['TEST_CREDENTIALS_SECRET']);

        $this->assertTrue(ConvertKit::tag('jane@example.com', __CLASS__));
        $this->assertTrue(is_int(ConvertKit::getTagId(__CLASS__)));
    }


    public function testCanUnsubscribeFromTag()
    {
        ConvertKit::fake(false);
        ConvertKit::credentials($_ENV['TEST_CREDENTIALS_KEY'], $_ENV['TEST_CREDENTIALS_SECRET']);

        $this->assertTrue(ConvertKit::untag('jane@example.com', __CLASS__));
    }
}
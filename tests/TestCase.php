<?php

namespace Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $faker;


    public function setUp()
    {
        $this->faker = \Faker\Factory::create();
    }
}
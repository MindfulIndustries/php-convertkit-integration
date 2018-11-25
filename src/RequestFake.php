<?php

namespace MindfulIndustries\Integrations\ConvertKit;

class RequestFake extends AbstractRequest implements RequestContract
{
    /** @var  */
    protected $faker;


    /** @var array */
    protected static $assertions = [];


    /** @inheritdoc */
    public function __construct($credentials)
    {
        parent::__construct($credentials);
        $this->faker = \Faker\Factory::create();
    }


    /** @inheritdoc */
    public function subscriberId(string $email) : ?int
    {
        static::incAssertions(__FUNCTION__);
        return $this->faker->randomDigit;
    }


    /** @inheritdoc */
    public function subscribe(string $email, int $formId, array $options = []) : bool
    {
        return $this->faker->boolean;
    }


    /** @inheritdoc */
    public function unsubscribe(string $email) : bool
    {
        return $this->faker->boolean;
    }


    /** @inheritdoc */
    public function tag(string $email, $tag) : bool
    {
        return $this->faker->boolean;
    }


    /** @inheritdoc */
    public function untag(string $email, $tag) : bool
    {
        return $this->faker->boolean;
    }


    /** @inheritdoc */
    public function getTagId(string $tag, bool $createIfDoesNotExist = false) : ?int
    {
        return $createIfDoesNotExist
            ? $this->faker->randomDigit
            : $this->faker->boolean;
    }


    /** @inheritdoc */
    public function getTags() : array
    {
        $amount = $this->faker->randomBetween(0, 7);
        $response = [];

        for ($i = 0; $i < $amount; $i++) {
            $response[] = [
                'id' => $this->faker->unique()->randomDigit(),
                'name' => $this->faker->unique()->catchPhrase,
                'created_at' => $this->faker->datetime->format(DATE_RFC850)
            ];
        }

        return $reponse;
    }


    /** @inheritdoc */
    public function createTag(string $tag) : ?int
    {
        return $this->faker->boolean
            ? $this->faker->randomDigit
            : null;
    }



    public function __call(string $method, $arguments)
    {
        if (static::startsWith($method, 'assertCalled')) {
            $methodToAssert = lcFirst(substr($method, strlen('assertCalled')));
            if (isset(static::$assertions[$methodToAssert])) {
                return count($arguments) == 1 && is_int($arguments[0])
                    ? static::$assertions[$methodToAssert] == $arguments[0]
                    : true;
            } else {
                return false;
            }
        } else {
            return $this->{$method}(...$arguments);
        }
    }


    /**
     * Determine if given Haystack starts with given Needle.
     * @param  string $haystack
     * @param  string $needle
     * @return bool
     */
    protected static function startsWith(string $haystack, string $needle) : bool
    {
        return (substr($haystack, 0, strlen($needle)) === $needle);
    }


    /**
     * Increment number of calls for given method.
     * @param  string $method
     * @param  int $amount
     * @return void
     */
    protected static function incAssertions(string $method, int $amount = 1)
    {
        if (isset(static::$assertions[$method])) {
            static::$assertions[$method] += $amount;
        } else {
            static::$assertions[$method] = $amount;
        }
    }

    protected function convertKitRequest(string $method, string $path, array $payload)
    {
        return null;
    }
}
<?php

namespace MindfulIndustries\Integrations\ConvertKit;

use InvalidArgumentException;

class ConvertKit
{
    /** @var array */
    protected static $credentialsPayload = [];


    /** @var \MindfulIndustries\Integrations\ConvertKit\Request */
    protected static $request = null;


    /**
     * Setup ConvertKit Credentials
     * @param  string $key
     * @param  string $secret
     * @return void
     */
    public static function credentials(string $key, string $secret)
    {
        static::$credentialsPayload = [
            'api_key' => $key,
            'api_secret' => $secret
        ];
    }


    /**
     * Enable (or disable) fake calls.
     * @param  bool $on
     * @return void;
     */
    public static function fake(bool $on = true)
    {
        static::$request = $on
            ? new RequestFake(static::$credentialsPayload)
            : null;
    }


    /**
     * Statically construct the Request object.
     * @param  string $method
     * @param  mixed $params
     * @return mixed
     */
    public static function __callStatic(string $method, $arguments)
    {
        if (is_null(static::$request)) {
            static::$request = new Request(static::$credentialsPayload);
        }

        return static::$request->{$method}(...$arguments);
    }
}
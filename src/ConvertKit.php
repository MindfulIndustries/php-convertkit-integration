<?php

namespace MindfulIndustries\Integrations\ConvertKit;

use InvalidArgumentException;

class ConvertKit
{
    /** @var Callable */
    protected static $credentials = [];


    /** @var \MindfulIndustries\Integrations\ConvertKit\Request */
    protected static $request = null;


    /**
     * Setup ConvertKit Credentials
     * @param  array|callable $callback
     * @return void
     * @throws \InvalidArgumentException
     */
    public static function credentials($callback)
    {
        if (is_array($credentials)) {
            static::$credentials = $callback;
        } elseif (is_callable($callback)) {
            static::$credentials = $callback();
        } else {
            throw new InvalidArgumentException;
        }
    }


    /**
     * Enable fake calls.
     * @return void;
     */
    public static function fake()
    {
        static::$request = new RequestFake(static::$credentials);
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
            static::$request = new Request(static::$credentials);
        }

        return static::$request->{$method}(...$arguments);
    }
}
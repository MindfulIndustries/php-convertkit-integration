<?php

namespace MindfulIndustries\Integrations\ConvertKit;

use InvalidArgumentException;

class ConvertKit
{
    /** @var Callable */
    protected static $credentials;


    /** @var \MindfulIndustries\Integrations\ConvertKit\Request */
    protected static $request = null;

    /**
     * Statically construct the Request object.
     * @param  string $method
     * @param  mixed $params
     * @return mixed
     */
    public static function __callStatic(string $method, $arguments)
    {
        if ($method == 'credentials') {
            if (count($arguments) != 1 || !is_callable($arguments[0])) {
                throw new InvalidArgumentException;
            } else {
                static::$credentials = $arguments[0]();
                return;
            }
        } elseif ($method == 'fake') {
            static::$request = new RequestFake(static::$credentials);
            return;
        }

        if (is_null(static::$request)) {
            static::$request = new Request(static::$credentials);
        }

        return static::$request->{$method}(...$arguments);
    }
}
<?php

namespace MindfulIndustries\Integrations\ConvertKit;

abstract class AbstractRequest
{
    /** @var mixed */
    protected $credentials = null;


    /**
     * @see \MindfulIndustries\Integrations\ConvertKit\RequestContract
     */
    public function __construct($credentials)
    {
        $this->credentials = $credentials;
    }


    protected static function get(string $path, array $payload = [])
    {
        return static::convertKitRequest('get', $path, $payload);
    }


    protected static function post(string $path, array $payload = [])
    {
        return static::convertKitRequest('post', $path, $payload);
    }


    protected static function delete(string $path, array $payload = [])
    {
        return static::convertKitRequest('delete', $path, $payload);
    }


    /**
     * Call ConvertKit
     * @param  string $method
     * @param  string $path
     * @param  array $payload
     * @return mixed
     */
    abstract protected function convertKitRequest(string $method, string $path, array $payload);
}
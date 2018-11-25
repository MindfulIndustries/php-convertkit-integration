<?php

namespace MindfulIndustries\Integrations\ConvertKit;

abstract class AbstractRequest
{
    /** @var mixed */
    protected $credentials = null;


    /**
     * @param  array $creadentials
     * @see    \MindfulIndustries\Integrations\ConvertKit\ConvertKit::credentials()
     * @return static
     */
    public function __construct($credentials)
    {
        $this->credentials = $credentials;
    }


    protected function get(string $path, array $payload = [])
    {
        return $this->convertKitRequest('get', $path, $payload);
    }


    protected function post(string $path, array $payload = [])
    {
        return $this->convertKitRequest('post', $path, $payload);
    }


    protected function delete(string $path, array $payload = [])
    {
        return $this->convertKitRequest('delete', $path, $payload);
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
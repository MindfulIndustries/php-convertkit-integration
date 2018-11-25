<?php

namespace MindfulIndustries\Integrations\ConvertKit;

use InvalidArgumentException;
use MindfulIndustries\Support\Transport\ConnectionException;
use MindfulIndustries\Support\Transport\Http;
use MindfulIndustries\Support\Transport\Response;

class Request extends AbstractRequest implements RequestContract
{
    /** @inheritdoc */
    public function subscriberId(string $email) : ?int
    {
        return $this->withResponse(
            static::get('/subscribers', ['email_address' => strtolower($email)]),
            function ($payload) {
                return $this->arrayGet($payload, 'total_subscribers') == 1
                    ? $this->arrayGet($payload, 'subscribers.0.id')
                    : null;
            }
        );
    }


    /** @inheritdoc */
    public function subscribe(string $email, int $formId, array $options = []) : bool
    {
        return static::post(
            sprintf('/forms/%d/subscribe', $formId),
            array_merge($options, ['email' => strtolower($email)])
        )->isOk();
    }


    /**
     * Get an item from an array using "dot" notation.
     * @param  array $array
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    protected function arrayGet(array $array, string $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        if (strpos($key, '.') === false) {
            return $array[$key] ?? value($default);
        }

        foreach (explode('.', $key) as $segment) {
            if (array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return value($default);
            }
        }

        return $array;
    }


    /**
     * Evaluate and return given Callback when given Response is Ok, otherwise null.
     * Response Json is passed to given Callback as Payload argument.
     * @param  \MindfulIndustries\Support\Transport\Response $response
     * @return mixed
     */
    protected function withResponse(?Response $response, $callback)
    {
        return $response->isOk()
            ? $callback($response->json())
            : null;
    }


    /** @inheritdoc */
    protected function convertKitRequest(string $method, string $path, array $payload) : Response
    {
        if (empty($path)) {
            throw new InvalidArgumentException;
        }

        if ($path[0] != '/') {
            $path = '/' . $path;
        }

        try {

            return Http::{$method}(
                sprintf('https://api.convertkit.com/v3%s', $path),
                array_merge($payload, [static::$credentials])
            );
        } catch (ConnectionException $e) {
            return new Response(new \GuzzleHttp\Psr7\Response(408));
        } catch (Exception $e) {
            return new Response(new \GuzzleHttp\Psr7\Response(422));
        }
    }
}
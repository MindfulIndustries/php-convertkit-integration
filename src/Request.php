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
            $this->get('/subscribers', ['email_address' => strtolower($email)]),
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
        return $this->post(
            sprintf('/forms/%d/subscribe', $formId),
            array_merge($options, ['email' => strtolower($email)])
        )->isOk();
    }


    /** @inheritdoc */
    public function unsubscribe(string $email) : bool
    {
        return $this->put(
            '/unsubscribe',
            ['email' => strtolower($email)]
        )->isOk();
    }


    /** @inheritdoc */
    public function tag(string $email, $tag) : bool
    {
        if (is_string($tag)) {
            if (is_null($tag = $this->getTagId($tag, true))) {
                return false;
            }
        }

        if (!is_int($tag)) {
            throw new InvalidArgumentException;
        }

        return static::post(
            sprintf('/tags/%d/subscribe', $tag),
            ['email' => strtolower($email)]
        )->isOk();
    }


    /** @inheritdoc */
    public function untag(string $email, $tag) : bool
    {
        if (is_string($tag)) {
            if (is_null($tag = $this->getTagId($tag, false))) {
                return true;
            }
        }

        if (!is_int($tag))  {
            throw new InvalidArgumentException;
        }

        return $this->post(
            sprintf('/tags/%d/unsubscribe', $tag),
            ['email' => strtolower($email)]
        )->isOk();
    }


    /** @inheritdoc */
    public function getTagId(string $tag, bool $createIfDoesNotExist = false) : ?int
    {
        $foundTags = array_filter($this->getTags(), function ($retrievedTag) use ($tag) {
            return $this->arrayGet($retrievedTag, 'name') == $tag;
        });

        if (count($foundTags) > 0 || $createIfDoesNotExist == false) {
            return $this->arrayGet(reset($foundTags), 'id');
        }

        return $this->withResponse(
            $this->post('/tags', ['tag' => ['name' => $tag]]),
            function ($payload) {
                return $this->arrayGet($payload, 'id');
            }
        );
    }


    /** @inheritdoc */
    public function getTags() : array
    {
        return $this->withResponse(
            $this->get('/tags'),
            function ($payload) {
                return $this->arrayGet($payload, 'tags');
            },
            []
        );
    }


    /**
     * Create Tag at ConvertKit and return its Id.
     * @param  string $tag
     * @return int|null
     */
    public function createTag(string $tag) : ?int
    {
        return $this->getTagId($tag, true);
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
     * @param  callable $callback
     * @param  mixed $default
     * @return mixed
     */
    protected function withResponse(Response $response, $callback, $default = null)
    {
        return $response->isOk()
            ? $callback($response->json())
            : $default;
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
                array_merge($payload, $this->credentials)
            );
        } catch (ConnectionException $e) {
            var_dump($e);
            return new Response(new \GuzzleHttp\Psr7\Response(408));
        } catch (Exception $e) {
            var_dump($e);
            return new Response(new \GuzzleHttp\Psr7\Response(422));
        }
    }
}
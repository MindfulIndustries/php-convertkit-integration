<?php

namespace MindfulIndustries\Integrations\ConvertKit;

use InvalidArgumentException;
use MindfulIndustries\Support\Transport\ConnectionException;
use MindfulIndustries\Support\Transport\Http;

class Request extends AbstractRequest implements RequestContract
{
    /** @inheritdoc */
    public function subscriberId(string $email) : ?int
    {
        $response = static::get('/subscribers', ['email_address' => strtolower($email)]);
        return $response->isOk() && $response->json()['total_subscribers'] == 1
            ? $response->json()['subscribers'][0]['id']
            : null;
    }


    /** @inheritdoc */
    protected function convertKitRequest(string $method, string $path, array $payload)
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

        }
    }
}
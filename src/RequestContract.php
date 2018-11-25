<?php

namespace MindfulIndustries\Integrations\ConvertKit;

interface RequestContract
{
    /**
     * Create an Instance.
     * @param  array $credentials (['api_key' => ..., 'api_secret' => ...])
     * @return static
     */
    public function __construct($credentials);


    /**
     * Retrieve ConvertKit ID for given subsscriber's email.
     * @param  string $email
     * @return int|null
     */
    public function subscriberId(string $email) : ?int;


    /**
     * Subscribe given Email to given Form.
     * @param  string $email
     * @param  int $formId (ConvertKit Form Id)
     * @param  array $options
     * @return bool
     */
    public function subscribe(string $email, int $formId, array $options = []) : bool;


    /**
     * Unsubsribe given Email.
     * @param  string $email
     * @return bool
     */
    public function unsubscribe(string $email) : bool;


    /**
     * Tag given User with Given Tag.
     * @param  string $email (when email given, ConvertKit Subscriber Id will be resolved)
     * @param  int|string $tag (when string given, ConvertKit Tag Id will be resolved or created)
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function tag(string $email, $tag) : bool;


    /**
     * Untag given User with Given Tag.
     * @param  string $email
     * @param  int|string $tag (when string given, ConvertKit Tag Id will be resolved or created)
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function untag(string $email, $tag) : bool;


    /**
     * Retrieve Tag Id.
     * Returns null when does not exist.
     * @param  string $tag
     * @param  bool $createIfDoesNotExist
     * @return int|null
     */
    public function getTagId(string $tag, bool $createIfDoesNotExist = false) : ?int;


    /**
     * Retrieve list of Tags at ConvertKit
     * @return array
     */
    public function getTags() : array;


    /**
     * Create Tag at ConvertKit and return its Id.
     * @param  string $tag
     * @return int|null
     */
    public function createTag(string $tag) : ?int;
}
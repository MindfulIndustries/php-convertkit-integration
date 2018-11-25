<?php

namespace MindfulIndustries\Integrations\ConvertKit;

interface RequestContract
{
    /**
     * Create an Instance.
     * @param  array $credentials (['key' => ..., 'secret' => ...])
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
     * Subscribe to Form.
     * @param  string $email
     * @param  int $formId (ConvertKit Form Id)
     * @param  array $options
     * @return bool
     */
    public function subscribe(string $email, int $formId, array $options = []) : bool;




}
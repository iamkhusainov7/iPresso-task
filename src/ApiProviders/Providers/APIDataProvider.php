<?php

namespace App\ApiProviders\Providers;

use Exception;

abstract class APIDataProvider
{
    protected string $apiLink;

    /**
     * The methods sends get request to the given endpoint and returns response body
     * 
     * @param string $endpoint
     * @param array $params - query parameters
     * @return array
     */
    abstract public function get(string $endpoint, array $params = []);

    abstract protected function handleResponce($response);

    /**
     * Builds full link to the api.
     * 
     * @param string $endpoint
     * @return string
     */
    protected function getLink(string $endpoint)
    {
        $endpoint = preg_replace("/^[\/]{1}/", '', $endpoint);
        
        return "{$this->apiLink}/{$endpoint}";
    }

    /**
     * Validates given endpoint
     * 
     * @param string $endpoint
     * @throws Exception
     * @return bool
     */
    protected function validateEndpoint(string $endpoint)
    {
        if (
            !preg_match("/^[a-z\/]{1}[a-z0-9\/\-]{0,}$/", $endpoint)
        ) {
            throw new Exception(
                sprintf(
                    'Invallid endpoint was given: "%s"',
                    $endpoint
                )
            );
        }

        return true;
    }
}

<?php

namespace App\ApiProviders;

use App\ApiProviders\Providers\APIDataProvider;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpClient\HttpClient;

class CurrencySource extends APIDataProvider
{
    public function __construct()
    {
        $this->apiLink = "http://api.nbp.pl/api";
    }

    public function get(string $endpoint, array $param = [])
    {
        $this->validateEndpoint($endpoint);

        $link = $this->getLink($endpoint);

        $response = HttpClient::create()->request('GET', $link, $param);

        return $this->handleResponce($response);
    }

    /**
     * Handles the response retreives the response body
     * 
     */
    protected function handleResponce($response)
    {
        $statusCode = $response->getStatusCode();

        if (
            $statusCode === 404
        ) {
            throw new NotFoundHttpException(
                sprintf(
                    'Error was returned with the status: "%s"',
                    $statusCode
                )
            );
        } else if (
            $statusCode >= 400 &&
            $statusCode <= 500
        ) {
            throw new Exception($response->getMessage());
        }

        return $response->toArray()[0]['rates'];
    }
}

<?php

namespace App\Model;

use GuzzleHttp\Client;
use Nette\Object;


class ApiConnector extends Object
{
    /** @var Client */
    private $client;

    /** @var string API endpoint */
    private $endpoint;

    public function __construct(string $endpoint = '', Client $client)
    {
        $this->endpoint = $endpoint;
        $this->client = $client;
    }

    public function signIn(string $login, string $password): string
    {
        $response = $this->client->post($this->endpoint . '/api/v1/sign/in', [
            'json' => [
                'email' => $login,
                'password' => $password
            ]
        ]);

        $responseBody = \GuzzleHttp\json_decode($response->getBody(), true);

        if ($response->getStatusCode() != 200) {
            throw new ApiException($responseBody['message'] . ' ' . ($responseBody['message'] ?? ''));
        }

        return $responseBody['token'];
    }
}

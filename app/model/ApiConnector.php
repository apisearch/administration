<?php

namespace App\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
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
        try {
            $response = $this->client->post($this->endpoint . '/api/v1/sign/in', [
                'json' => [
                    'email' => $login,
                    'password' => $password
                ]
            ]);
        } catch (BadResponseException $e) {
            $responseBody = \GuzzleHttp\json_decode($e->getResponse()->getBody(), true);
            throw new ApiException($responseBody['description'] ?: $responseBody['message'] ?? '');
        }

        $responseBody = \GuzzleHttp\json_decode($response->getBody(), true);

        if ($response->getStatusCode() != 200) {
            throw new ApiException($responseBody['message'] . ' ' . ($responseBody['message'] ?? ''));
        }

        return $responseBody['token'];
    }

    public function register($login, $password, $xml)
    {
        try {
            $response = $this->client->post($this->endpoint . '/api/v1/user', [
                'json' => [
                    'email' => $login,
                    'password' => $password,
                    'feedUrl' => $xml,
                    'feedFormat' => 'heureka'
                ]
            ]);
        } catch (BadResponseException $e) {
            $responseBody = \GuzzleHttp\json_decode($e->getResponse()->getBody(), true);
            throw new ApiException($responseBody['description'] ?: $responseBody['message'] ?? '');
        }

        $responseBody = \GuzzleHttp\json_decode($response->getBody(), true);

        if ($response->getStatusCode() != 200) {
            throw new ApiException($responseBody['message'] . ' ' . ($responseBody['description'] ?? ''));
        }

        return $responseBody['token'];
    }
}

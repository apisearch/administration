<?php

namespace App\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Nette\Object;
use Psr\Http\Message\ResponseInterface;


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
        $response = null;

        try {
            $response = $this->client->post($this->endpoint . '/api/v1/sign/in', [
                'json' => [
                    'email' => $login,
                    'password' => $password
                ]
            ]);
        } catch (BadResponseException $e) {
            $this->processBadResponse($e);
        }

        return $this->decodeResponse($response)['token'];
    }

    public function signOut(string $token): bool
    {
        $response = null;

        try {
            $response = $this->client->get($this->endpoint . '/api/v1/sign/out/' . $token);
        } catch (BadResponseException $e) {
            $this->processBadResponse($e);
        }

        return $this->decodeResponse($response)['status'] == 200;
    }

    public function register($login, $password, $xml)
    {
        $response = null;

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
            $this->processBadResponse($e);
        }

        return $this->decodeResponse($response)['token'];
    }

    public function getUser(string $token)
    {
        $response = null;

        try {
            $response = $this->client->get($this->endpoint . '/api/v1/user/' . $token);
        } catch (BadResponseException $e) {
            $this->processBadResponse($e);
        }

        return $this->decodeResponse($response);
    }

    public function search(string $userId, string $query): array
    {
        $response = null;

        try {
            $response = $this->client->get($this->endpoint . '/api/v1/search/' . $userId . '/' . trim($query));
        } catch (BadResponseException $e) {
            $this->processBadResponse($e);
        }

        return $this->decodeResponse($response);
    }

    private function processBadResponse(BadResponseException $e)
    {
        $responseBody = \GuzzleHttp\json_decode($e->getResponse()->getBody(), true);
        throw new ApiException(($responseBody['description'] ?? null) ?: ($responseBody['message'] ?? ''));
    }

    private function decodeResponse(ResponseInterface $response): array
    {
        $responseBody = \GuzzleHttp\json_decode($response->getBody(), true);

        if ($response->getStatusCode() != 200) {
            throw new ApiException(($responseBody['message'] ?? '') . ' ' . ($responseBody['message'] ?? ''));
        }

        return $responseBody;
    }

    public function getSearchEndpoint(string $userId, string $query): string
    {
        return $this->endpoint . '/api/v1/search/' . $userId . '/' . urlencode($query);
    }
}

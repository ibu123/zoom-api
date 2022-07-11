<?php

namespace App\Service;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;

Class Zoom{

    private static $instance = null;
    private $apiKey;
    private $apiSecret;
    private $client;

    private function __construct()
    {
        $this->apiKey = env('ZOOM_API_KEY');
        $this->apiSecret = env('ZOOM_API_SECRET');
        $this->client = new Client();
        $this->apiPoint = 'https://api.zoom.us/v2/';
    }

    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new Zoom();
        }
        return self::$instance;
    }
     /**
     * Headers
     *
     * @return array
     */
    protected function headers(): array {
        return [
            'Authorization' => 'Bearer ' . $this->generateJWT(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Generate J W T
     *
     * @return string
     */
    protected function generateJWT() {
        $token = [
            'iss' => $this->apiKey,
            'exp' => time() + 60,
        ];

        return JWT::encode($token, $this->apiSecret);
    }


    /**
     * Get
     *
     * @param $method
     * @param array $fields
     * @return array|mixed
     */
    public function get($method, $fields = []) {
        try {
            $response = $this->client->request('GET', $this->apiPoint . $method, [
                'query' => $fields,
                'headers' => $this->headers(),
            ]);

            return $this->result($response);

        } catch (ClientException $e) {

            return (array)json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    /**
     * Post
     *
     * @param $method
     * @param $fields
     * @return array|mixed
     */
    public function post($method, $fields) {
        $body = \json_encode($fields, JSON_PRETTY_PRINT);

        try {
            $response = $this->client->request('POST', $this->apiPoint . $method,
                ['body' => $body, 'headers' => $this->headers()]);

            return $this->result($response);

        } catch (ClientException $e) {

            return (array)json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    /**
     * Patch
     *
     * @param $method
     * @param $fields
     * @return array|mixed
     */
    public function patch($method, $fields) {
        $body = \json_encode($fields, JSON_PRETTY_PRINT);

        try {
            $response = $this->client->request('PATCH', $this->apiPoint . $method,
                ['body' => $body, 'headers' => $this->headers()]);

            return $this->result($response);

        } catch (ClientException $e) {

            return (array)json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    /**
     * Put
     *
     * @param $method
     * @param $fields
     * @return array|mixed
     */
    public function put($method, $fields) {
        $body = \json_encode($fields, JSON_PRETTY_PRINT);

        try {
            $response = $this->client->request('PUT', $this->apiPoint . $method,
                ['body' => $body, 'headers' => $this->headers()]);

            return $this->result($response);

        } catch (ClientException $e) {

            return (array)json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    /**
     * Delete
     *
     * @param $method
     * @param $fields
     * @return array|mixed
     */
    public function delete($method, $fields = []) {
        $body = \json_encode($fields, JSON_PRETTY_PRINT);

        try {
            $response = $this->client->request('DELETE', $this->apiPoint . $method,
                ['body' => $body, 'headers' => $this->headers()]);

            return $this->result($response);

        } catch (ClientException $e) {

            return (array)json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    /**
     * Result
     *
     * @param Response $response
     * @return mixed
     */
    public function result(Response $response) {
        $result = json_decode((string)$response->getBody(), true);

        $result['code'] = $response->getStatusCode();

        return $result;
    }
}

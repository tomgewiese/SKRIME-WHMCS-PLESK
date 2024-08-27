<?php

use GuzzleHttp\Client;

class SkrimePleskLicenseApiClient {

	private $token = null;
    /**
     * @var string
     */
    private $url;

    public function __construct($token) {
		$this->token = $token;
		$this->url = 'https://skrime.eu/api/';
	}
	
	public function get($url, $params = []) {
		return $this->request($url, $params, 'GET');
	}
	
	public function post($url, $params = []) {
		return $this->request($url, $params, 'POST');
	}
	
	public function delete($url, $params = []) {
		return $this->request($url, $params, 'DELETE');
	}
	
	public function put($url, $params = []) {
		return $this->request($url, $params, 'PUT');
	}
	
	private function request($url, $parameters = [], $method) {
        $apiKey = $this->token;

        $client = new Client();
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => $parameters,
        ];

        $response = $client->request($method, $this->url . $url, $options);
        return json_decode($response->getBody(), true);
	}
	
}
<?php


namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiClient
{
    private $client;
    private $urlSuffix = '';
    private $url;

    public function __construct(string $serverApiUrl)
    {
        $this->client = new Client();
        $this->url = $serverApiUrl;
    }

    public function setUrlSuffix($suffix)
    {
        $this->urlSuffix = $suffix;
    }

    public function getUrl()
    {
        return $this->url . $this->urlSuffix;
    }

    public function get()
    {
        $response = $this->sendRequest($this->getUrl(), 'GET');
        $content =  $response->getBody()->getContents();
        return json_decode($content, true);
    }

    public function post($data)
    {
        $params = [
            'allow_redirects' => false,
            'body' => json_encode($data),
        ];
        $response = $this->sendRequest($this->getUrl(), 'POST', $params);
        $content =  $response->getBody()->getContents();
        return json_decode($content, true);
    }

    public function patch($id, $data)
    {
        $params = [
            'allow_redirects' => false,
            'body' => json_encode($data),
        ];
        $response = $this->sendRequest($this->getUrl() . $id, 'PATCH', $params);
        $content =  $response->getBody()->getContents();
        return json_decode($content, true);
    }

    public function delete($id)
    {
        $this->sendRequest($this->getUrl() . $id, 'DELETE');
    }

    public function getGroupUsers($id)
    {
        $response = $this->sendRequest($this->getUrl() . "get-users/{$id}", 'GET');
        $content =  $response->getBody()->getContents();
        return json_decode($content, true);
    }

    private function sendRequest($url, $method, $params = ['allow_redirects' => false])
    {
        try {
            $response = $this->client->request($method, $url, $params);
        }
        catch (RequestException $e) {
            $response = $e->getResponse();
            dd(['code' => $response->getStatusCode(), 'error' => $response->getReasonPhrase()]);
        }

        return $response;
    }
}
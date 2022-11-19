<?php

namespace App\Service;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FetchApiService
{
    private $client;

    public function __construct(HttpClientInterface $client){
        $this->client = $client;
    }

    public function fetchRssFlux($url) 
    {
        try {
            $data = simplexml_load_file($url, null, LIBXML_NOCDATA);
            return json_encode($data);
        } catch (\Throwable $th) {
            throw new Exception('Une erreur est survenue');
        }
    }

    public function fetchJson($url)
    {
        try {
            $response = $this->client->request('GET', $url);
            return json_decode($response->getContent());
        } catch (\Throwable $th) {
            throw new Exception('Une erreur est survenue');
        }
    }
    
}

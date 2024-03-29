<?php

namespace Brknesn\LaravelMultiGwSmsProvider\Gateway;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class KobikomSmsGateway implements SmsGateway
{
    private Client $client;
    private array $config;


    public function __construct(array $config)
    {
        $base_uri = rtrim($config['api'], '/');
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => $base_uri,
            'headers' => [
                'Content-Type' => 'application/xml',
                'Accept' => '*/*',
            ],
        ]);
    }

    public function getName() : string {
        return 'kobikom';
    }
    /**
     * @throws GuzzleException
     * @throws \JsonException
     */
     public function send($title, $message, array $receivers, $type = null)
    {
        $response = $this->client->request('GET', '',[
            'query' => ['to' => $receivers[0], 'from' => $this->config['from'], 'sms' => $message,'action' => 'send-sms','api_key' => $this->config['apiKey']],
        ]);
        $response = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        if($response['code'] !== 'ok'){
            throw new \Exception($response['message']);
        }
        return $response;
    }
}

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
        $base_uri = rtrim($config['api'], '/').'/multiple?action=send-sms&api_key='.$config['apiKey'];
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
    public function send($title, $message, array $receivers, $type = null): string
    {
        $body = [];
        foreach($receivers as $receiver) {
            $body[] = [
                'to' => $receiver,
                'from' => $this->config['from'],
                'sms' => $message,
            ];
        }
        $response = $this->client->request('POST', '', [
            'body' => json_encode($body, JSON_THROW_ON_ERROR),
        ]);
        return $response->getBody()->getContents();
    }
}

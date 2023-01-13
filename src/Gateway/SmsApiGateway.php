<?php

namespace Brknesn\LaravelMultiGwSmsProvider\Gateway;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SmsApiGateway implements SmsGateway
{
    private Client $client;
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new Client([
            'base_uri' => $config['api'],
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$config['apiKey']
            ],
        ]);
    }

    public function getName() : string {
        return 'smsapi';
    }
    /**
     * @throws GuzzleException
     * @throws \JsonException
     */
    public function send($title, $message, array $receivers, $type = null): string
    {

        $receivers = implode(PHP_EOL, $receivers);

        $params = array(
            'to'      => $receivers,
            'from'    => $this->config['from'],
            'message' => $message,
            'format'  => 'json',
        );

        $response = $this->client->request('POST', 'sms.do', [
            'body' => json_encode($params, JSON_THROW_ON_ERROR),
        ]);
        return $response->getBody()->getContents();
    }
}

<?php

return [
    'default' => 'kobikom',
    'gateways' => [
        'kobikom' => [
            'class' => \Brknesn\LaravelMultiGwSmsProvider\Gateway\KobikomSmsGateway::class,
            'apiKey' => '',
            'api' => 'https://smspaneli.kobikom.com.tr/sms/api',
            'from' => '',
            'regex' => '/^\+90/',
        ],
        'smsapi' => [
            'class' => \Brknesn\LaravelMultiGwSmsProvider\Gateway\SmsApiGateway::class,
            'api' => 'https://api.smsapi.com/sms.do',
            'apiKey' => '',
            'from' => '',
            'regex' => '/^\+[0-9]{2}/',
        ],
        // Additional gateways can be added here
    ],
    'queue' => [
        'enabled' => false,
        'queue_name' => 'sms',
        'connection' => 'redis',
    ],
];

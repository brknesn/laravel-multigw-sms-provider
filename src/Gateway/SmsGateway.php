<?php

namespace Brknesn\LaravelMultiGwSmsProvider\Gateway;

interface SmsGateway
{
    public function getName(): string;
    public function send($title, $message, array $receivers, $type);
}

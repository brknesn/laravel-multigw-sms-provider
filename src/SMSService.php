<?php

namespace Brknesn\LaravelMultiGwSmsProvider;

class SMSService
{
    protected mixed $gateway;
    protected array $recipients = [];
    private $message;
    private $title;
    private $type;

    protected function getGateway($phoneNumber)
    {
        $defaultGateway = config('laravel-multigw-sms-provider.default');
        if (!$defaultGateway) {
            throw new \RuntimeException('No default gateway is set');
        }
        $gateways = config('laravel-multigw-sms-provider.gateways');
        $gatewayConfig = $gateways[$defaultGateway];
        foreach ($gateways as $key => $value) {
            if ($value['regex'] && preg_match($value['regex'], $phoneNumber)) {
                $gatewayConfig = $value;
                break;
            }
        }
        $gateway = new $gatewayConfig['class']($gatewayConfig);
        return $gateway;
    }

    public function setMessage($message): SMSService
    {
        $this->message = $message;

        return $this;
    }

    public function setTitle($title): SMSService
    {
        $this->title = $title;

        return $this;
    }

    public function setType($type): SMSService
    {
        $this->type = $type;

        return $this;
    }
    public function addRecipient($receiver)
    {
        $this->recipients[] = str_replace([' ', '(', ')', '-','+'], '', $receiver);
        return $this;
    }

    public function splitNumbersByGateway(): array
    {
        $gateways = [];
        foreach ($this->recipients as $recipient) {
            $gateway = $this->getGateway($recipient);
            $gateways[$gateway->getName()][] = $recipient;
        }
        return $gateways;
    }

    public function send(): void
    {
        $gateways = $this->splitNumbersByGateway();
        foreach ($gateways as $recipients) {
            $gateway = $this->getGateway($recipients[0]);
            $gateway->send($this->title, $this->message, $recipients, $this->type);
        }
    }
}

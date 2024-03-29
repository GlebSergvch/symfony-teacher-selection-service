<?php

namespace App\Dto;

class SendNotificationDTO
{
    private array $payload;

    public function __construct(int $userId, string $text)
    {
        $this->payload = ['userId' => $userId, 'text' => $text];
    }

    public function toAMQPMessage(): string
    {
        return json_encode($this->payload, JSON_THROW_ON_ERROR);
    }
}
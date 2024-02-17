<?php

namespace App\Dto;

use JsonException;
class SkillDto
{
    private array $payload;

    public function __construct(int $skillId, string $skillName)
    {
        $this->payload = ['skillId' => $skillId, 'skillName' => $skillName];
    }

    /**
     * @throws JsonException
     */
    public function toAMQPMessage(): string
    {
        return json_encode($this->payload, JSON_THROW_ON_ERROR);
    }
}
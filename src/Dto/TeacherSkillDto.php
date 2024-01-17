<?php

namespace App\Dto;

use JsonException;
class TeacherSkillDto
{
    private array $payload;

    public function __construct(array $teachers, array $skills)
    {
        $this->payload = ['teachers' => $teachers, 'skills' => $skills];
    }

    /**
     * @throws JsonException
     */
    public function toAMQPMessage(): string
    {
        return json_encode($this->payload, JSON_THROW_ON_ERROR);
    }
}
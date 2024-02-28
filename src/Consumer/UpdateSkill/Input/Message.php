<?php

namespace App\Consumer\UpdateSkill\Input;

use Symfony\Component\Validator\Constraints as Assert;

class Message
{
    #[Assert\Type('numeric')]
    private int $skillId;

    #[Assert\Type('string')]
    #[Assert\Length(max: 60)]
    private string $skillName;

    public static function createFromQueue(string $messageBody): self
    {
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);
        $result = new self();
        $result->skillId = $message['skillId'];
        $result->skillName = $message['skillName'];

        return $result;
    }

    public function getSkillId(): int
    {
        return $this->skillId;
    }

    public function getSkillName(): string
    {
        return $this->skillName;
    }
}
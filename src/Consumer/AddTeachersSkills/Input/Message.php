<?php

namespace App\Consumer\AddTeachersSkills\Input;

use Symfony\Component\Validator\Constraints as Assert;

class Message
{
    #[Assert\Type('array')]
    private array $teachers;

    #[Assert\Type('array')]
    private array $skills;

    public static function createFromQueue(string $messageBody): self
    {
        $message = json_decode($messageBody, true, 512, JSON_THROW_ON_ERROR);
        $result = new self();
        $result->teachers = $message['teachers'];
        $result->skills = $message['skills'];;

        return $result;
    }

    public function getTeachers(): array
    {
        return $this->teachers;
    }

    public function getSkills(): array
    {
        return $this->skills;
    }
}
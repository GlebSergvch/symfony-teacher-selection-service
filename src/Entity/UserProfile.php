<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;

#[ORM\Table(name: '`user_profile`')]
#[ORM\Entity]
class UserProfile
{
    #[ORM\Column(name: 'user_id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $user_id = null;

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private string $firstname;

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private string $middlename;

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private string $lastname;

    #[ORM\Column(type: 'string', length: 32, nullable: true)]
    private string $gender;

    #[ORM\OneToOne(inversedBy: "userProfile", targetEntity: "User")]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private $user;

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $userId): void
    {
        $this->user_id = $userId;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getMiddlename(): string
    {
        return $this->middlename;
    }

    public function setMiddlename(string $middlename): void
    {
        $this->middlename = $middlename;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }
}
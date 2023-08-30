<?php

namespace App\Entity;

use App\Enum\UserProfileGender;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use JetBrains\PhpStorm\ArrayShape;

#[ORM\Table(name: '`user_profile`')]
#[ORM\Entity]
#[ORM\Index(columns: ['created_by'], name: 'user_profile__created_by__idx')]
#[ORM\Index(columns: ['updated_by'], name: 'user_profile__updated_by__idx')]
class UserProfile
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private string $firstname;

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private string $middlename;

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private string $lastname;

    #[ORM\Column(type: 'string', length: 32, nullable: true, enumType: UserProfileGender::class)]
    public UserProfileGender $gender;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $age;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private DateTime $updatedAt;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'created_by', referencedColumnName: 'id')]
    private User $createdBy;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'updated_by', referencedColumnName: 'id')]
    private User $updatedBy;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
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

    public function getGender(): UserProfileGender
    {
        return $this->gender;
    }

    public function setGender(UserProfileGender $gender): void
    {
        $this->gender = $gender;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    public function setCreatedAt(): void {
        $this->createdAt = new DateTime();
    }

    public function getUpdatedAt(): DateTime {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): void {
        $this->updatedAt = new DateTime();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'middlename' => $this->middlename,
            'lastname' => $this->lastname,
            'gender' => $this->gender
        ];
    }

    public function getFullname()
    {
        return trim("$this->lastname $this->firstname $this->middlename");
    }
}
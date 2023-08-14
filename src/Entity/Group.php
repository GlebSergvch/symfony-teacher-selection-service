<?php

namespace App\Entity;

use App\Repository\GroupRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;
use JetBrains\PhpStorm\ArrayShape;

#[ORM\Table(name: '`group`')]
#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[UniqueConstraint(name: "group__name__uniq__idx", columns: ["name"])]
#[ORM\Index(columns: ['created_by'], name: 'group__created_by__idx')]
#[ORM\Index(columns: ['updated_by'], name: 'group__updated_by__idx')]
class Group
{
    const DEFAULT_MINIMUM_SIZE = 10;
    const DEFAULT_MAXIMUM_SIZE = 20;

    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 128, unique:true, nullable: false)]
    private string $name;

    #[ORM\Column(type: 'integer', nullable: false)]
    private string $minimumSize;

    #[ORM\Column(type: 'integer', nullable: false)]
    private string $maximumSize;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getMinimumSize(): int
    {
        return $this->minimumSize;
    }

    public function setMinimumSize(int $minimumSize): void
    {
        $this->minimumSize = $minimumSize;
    }

    public function getMaximumSize(): int
    {
        return $this->maximumSize;
    }

    public function setMaximumSize(int $maximumSize): void
    {
        $this->maximumSize = $maximumSize;
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

    #[ArrayShape([
        'id' => 'int|null',
        'name' => 'string',
        'minimumSize' => 'integer',
        'maximumSize' => 'integer',
        'createdAt' => 'string',
        'updatedAt' => 'string'
    ])]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'minimumSize' => $this->minimumSize,
            'maximumSize' => $this->maximumSize,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
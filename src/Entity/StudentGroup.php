<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Table(name: '`student_group`')]
#[ORM\Entity]
#[ORM\Index(columns: ['student_id'], name: 'student_group__student_id__idx')]
#[ORM\Index(columns: ['group_id'], name: 'student_group__group_id__idx')]
#[UniqueConstraint(name: "student_group__student_id__group_id__uniq", columns: ["student_id", "group_id"])]
class StudentGroup
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'student_id', referencedColumnName: 'id')]
    private ?int $student_id = null;

    #[ManyToOne(targetEntity: Group::class)]
    #[JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private ?int $group_id = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setStudentId(int $studentId): void
    {
        $this->student_id = $studentId;
    }

    public function getStudentId(): int
    {
        return $this->student_id;
    }

    public function setGroupId(int $groupId): void
    {
        $this->group_id = $groupId;
    }

    public function getGroupId(): int
    {
        return $this->group_id;
    }
}
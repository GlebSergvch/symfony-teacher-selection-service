<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Table(name: '`student_group`')]
#[ORM\Entity]
#[ORM\Index(columns: ['student_id'], name: 'student_group__student_id__idx')]
#[ORM\Index(columns: ['group_id'], name: 'student_group__group_id__idx')]
#[ORM\Index(columns: ['created_by'], name: 'student_group__created_by__idx')]
#[ORM\Index(columns: ['updated_by'], name: 'student_group__updated_by__idx')]
#[UniqueConstraint(name: "student_group__student_id__group_id__uniq", columns: ["student_id", "group_id"])]
class StudentGroup
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

//    #[ManyToOne(targetEntity: User::class)]
//    #[JoinColumn(name: 'student_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\Column(name: 'student_id', type: 'bigint')]
    private ?int $student_id = null;

//    #[ManyToOne(targetEntity: Group::class)]
//    #[JoinColumn(name: 'group_id', referencedColumnName: 'id', nullable: false)]

    #[ORM\Column(name: 'group_id', type: 'bigint')]
    private ?int $group_id = null;

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

    #[ManyToOne(targetEntity: User::class, inversedBy: 'studentGroups')]
    #[JoinColumn(name: 'student_id', referencedColumnName: 'id')]
    private User $student;

    #[ManyToOne(targetEntity: Group::class)]
    #[JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    public Group $group;

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

    public function setStudent(User $student): void
    {
        $this->student = $student;
    }

    public function getStudent(): User
    {
        return $this->student;
    }

    public function setGroup(Group $group): void
    {
        $this->group = $group;
    }

    public function getGroup(): Group
    {
        return $this->group;
    }

    public function getGroupName()
    {
        return $this->getGroup()->getName();
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
}
<?php

namespace App\Entity;

use App\Repository\TeacherSkillRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;
use JetBrains\PhpStorm\ArrayShape;

#[ORM\Entity(repositoryClass: TeacherSkillRepository::class)]
#[ORM\Table(name: '`teacher_skill`')]
#[ORM\Index(columns: ['teacher_id'], name: 'teacher_skill__teacher_id__idx')]
#[ORM\Index(columns: ['skill_id'], name: 'teacher_skill__skill_id__idx')]
#[ORM\Index(columns: ['created_by'], name: 'teacher_skill__created_by__idx')]
#[ORM\Index(columns: ['updated_by'], name: 'teacher_skill__updated_by__idx')]
#[UniqueConstraint(name: "teacher_skill__teacher_id__skill_id__uniq", columns: ["teacher_id", "skill_id"])]
class TeacherSkill
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'teacher_id', type: 'bigint')]
    private int $teacher_id;

    #[ORM\Column(name: 'skill_id', type: 'bigint')]
    private int $skill_id;

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

    #[ManyToOne(targetEntity: User::class, inversedBy: 'teacherSkills')]
    #[JoinColumn(name: 'teacher_id', referencedColumnName: 'id')]
    private User $teacher;

    #[ManyToOne(targetEntity: Skill::class)]
    #[JoinColumn(name: 'skill_id', referencedColumnName: 'id')]
    public Skill $skill;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setTeacherId(int $teacherId): void
    {
        $this->teacher_id = $teacherId;
    }

    public function getTeacherId(): int
    {
        return $this->teacher_id;
    }

    public function setTeacher(User $teacher): void
    {
        $this->teacher = $teacher;
    }

    public function getTeacher(): User
    {
        return $this->teacher;
    }

    public function setSkillId(int $skillId): void
    {
        $this->skill_id = $skillId;
    }

    public function getSkillId(): int
    {
        return $this->skill_id;
    }

    public function setSkill(Skill $skill)
    {
        $this->skill = $skill;
    }

    public function getSkill(): Skill
    {
        return $this->skill;
    }

    #[ArrayShape(['id' => 'int', 'teacher' => 'string', 'skill' => 'string', 'createdAt' => 'string', 'updatedAt' => 'string'])]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'teacher' => $this->getTeacher()->getuserProfile() ? $this->getTeacher()->getuserProfile()?->getFullname() : $this->getTeacher()->getLogin(),
            'skill' => $this->getSkill()->getName(),
            'createdAt' => $this->updatedAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }

    public function getSkillName()
    {
        return $this->getSkill()->getName();
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
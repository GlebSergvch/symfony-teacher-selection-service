<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[ORM\Entity]
#[ORM\Table(name: '`teacher_skill`')]
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

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'teacher_id', referencedColumnName: 'id')]
    private int $teacher;

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

    public function setSkillId(int $skillId): void
    {
        $this->skill_id = $skillId;
    }

    public function getSkillId(): int
    {
        return $this->skill_id;
    }
}
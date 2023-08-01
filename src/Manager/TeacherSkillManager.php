<?php

namespace App\Manager;

use App\Entity\Skill;
use App\Entity\TeacherSkill;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class TeacherSkillManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function create(User $teacher, Skill $skill): TeacherSkill
    {
        $teacherSkill = new TeacherSkill();
        $teacherSkill->setTeacher($teacher);
        $teacherSkill->setSkill($skill);
        $teacherSkill->setCreatedAt();
        $teacherSkill->setUpdatedAt();
        $this->entityManager->persist($teacherSkill);
        $this->entityManager->flush();

        return $teacherSkill;
    }

}
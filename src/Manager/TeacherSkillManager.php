<?php

namespace App\Manager;

use App\Entity\TeacherSkill;
use Doctrine\ORM\EntityManagerInterface;

class TeacherSkillManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function create(int $skillId, int $teacherId): TeacherSkill
    {
        $teacherSkill = new TeacherSkill();
        $teacherSkill->setTeacherId($teacherId);
        $teacherSkill->setSkillId($skillId);
        $this->entityManager->persist($teacherSkill);
        $this->entityManager->flush();

        return $teacherSkill;
    }
}
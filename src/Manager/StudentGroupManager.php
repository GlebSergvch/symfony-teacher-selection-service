<?php

namespace App\Manager;

use App\Entity\Group;
use App\Entity\Skill;
use App\Entity\StudentGroup;
use App\Entity\TeacherSkill;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class StudentGroupManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function create(User $student, Group $group): StudentGroup
    {
        $studentGroup = new StudentGroup();
        $studentGroup->setStudent($student);
        $studentGroup->setGroup($group);
        $studentGroup->setCreatedAt();
        $studentGroup->setUpdatedAt();
        $this->entityManager->persist($studentGroup);
        $this->entityManager->flush();

        return $studentGroup;
    }

}
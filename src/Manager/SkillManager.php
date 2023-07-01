<?php

namespace App\Manager;

use App\Entity\Skill;
use Doctrine\ORM\EntityManagerInterface;

class SkillManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }


    public function create(string $skillName): Skill
    {
        $skill = new Skill();
        $skill->setName($skillName);
        $skill->setCreatedAt();
        $skill->setUpdatedAt();
        $this->entityManager->persist($skill);
        $this->entityManager->flush();

        return $skill;
    }
}
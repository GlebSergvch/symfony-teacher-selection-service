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

    public function findSkillByName(string $name): array
    {
        return $this->entityManager->getRepository(Skill::class)->findBy(['name' => $name]);
    }

    public function findSkillById(int $id): array
    {
        return $this->entityManager->getRepository(Skill::class)->findBy(['id' => $id]);
    }

    public function findOrCreateSkill(string $skillName): Skill
    {
        $skill = $this->entityManager->getRepository(Skill::class)->findOneBy(['name' => $skillName]);
        if (!$skill) {
            $skill = $this->create($skillName);
        }

        return $skill;
    }
}
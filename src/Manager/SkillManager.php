<?php

namespace App\Manager;

use App\Entity\Skill;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;

class SkillManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }


    /**
     * @param string $skillName
     * @return Skill
     */
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

    /**
     * @param string $skillName
     * @return int
     */
    public function saveSkill(string $skillName): Skill
    {
        $skill = new Skill();
        $skill->setName($skillName);
        $skill->setCreatedAt();
        $skill->setUpdatedAt();
        $this->entityManager->persist($skill);
        $this->entityManager->flush();

        return $skill;
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return Skill[]
     */
    public function getSkills(int $page, int $perPage): array
    {
        /** @var SkillRepository $skillRepository */
        $skillRepository = $this->entityManager->getRepository(Skill::class);
        return $skillRepository->getSkills($page, $perPage);
    }

    /**
     * @param int $id
     * @return Skill|object|null
     */
    public function getSkillById(int $id)
    {
        $skillRepository = $this->entityManager->getRepository(Skill::class);
        return $skillRepository->findOneBy(['id' => $id]);
    }

    /**
     * @param string $name
     * @return Skill[]
     */
    public function findSkillByName(string $name): array
    {
        return $this->entityManager->getRepository(Skill::class)->findBy(['name' => $name]);
    }

    /**
     * @param int $id
     * @return Skill[]
     */
    public function findSkillById(int $id): array
    {
        return $this->entityManager->getRepository(Skill::class)->findBy(['id' => $id]);
    }

    /**
     * @param string $skillName
     * @return Skill
     */
    public function findOrCreateSkill(string $skillName): Skill
    {
        $skill = $this->entityManager->getRepository(Skill::class)->findOneBy(['name' => $skillName]);
        if (!$skill) {
            $skill = $this->create($skillName);
        }

        return $skill;
    }

    /**
     * @param int $skillId
     * @param string $name
     * @return bool
     */
    public function updateSkill(int $skillId, string $name): bool
    {
        /** @var SkillRepository $skillRepository */
        $skillRepository = $this->entityManager->getRepository(Skill::class);
        /** @var Skill $skill */
        $skill = $skillRepository->find($skillId);
        if ($skill === null) {
            return false;
        }
        $skill->setName($name);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param int $skillId
     * @return bool
     */
    public function deleteSkill(int $skillId): bool
    {
        /** @var SkillRepository $skillRepository */
        $skillRepository = $this->entityManager->getRepository(Skill::class);
        /** @var Skill $skill */
        $skill = $skillRepository->find($skillId);
        if ($skill === null) {
            return false;
        }
        $this->entityManager->remove($skill);
        $this->entityManager->flush();

        return true;
    }
}
<?php

namespace App\Manager;

use App\Entity\Group;
use App\Entity\Skill;
use Doctrine\ORM\EntityManagerInterface;

class GroupManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }


    public function create(string $groupName): Group
    {
        $group = new Group();
        $group->setName($groupName);
        $group->setMinimumSize(Group::DEFAULT_MINIMUM_SIZE);
        $group->setMaximumSize(Group::DEFAULT_MAXIMUM_SIZE);
        $group->setCreatedAt();
        $group->setUpdatedAt();
        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return $group;
    }

    public function findGroupByName(string $name): array
    {
        return $this->entityManager->getRepository(Group::class)->findBy(['name' => $name]);
    }

    public function findOrCreateGroup(string $groupName): Group
    {
        $group = $this->entityManager->getRepository(Group::class)->findOneBy(['name' => $groupName]);
        if (!$group) {
            $group = $this->create($groupName);
        }

        return $group;
    }
}
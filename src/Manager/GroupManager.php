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
        $group->setMinimumSize(12);
        $group->setMaximumSize(23);
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
}
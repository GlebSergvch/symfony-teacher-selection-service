<?php

namespace App\Manager;

use App\Entity\Group;
use App\Entity\Skill;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class GroupManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @return Group
     */
    public function create(string $groupName, int|null $minimumSize, int|null $maximumSize): Group
    {
        $group = new Group();
        $group->setName($groupName);
        $group->setMinimumSize($minimumSize);
        $group->setMaximumSize($maximumSize);
        $group->setCreatedAt();
        $group->setUpdatedAt();
        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return $group;
    }

    /**
     * @return Group[]
     */
    public function findGroupByName(string $name): array
    {
        return $this->entityManager->getRepository(Group::class)->findBy(['name' => $name]);
    }

    /**
     * @param string $groupName
     * @return Group
     */
    public function findOrCreateGroup(string $groupName): Group
    {
        $group = $this->entityManager->getRepository(Group::class)->findOneBy(['name' => $groupName]);
        if (!$group) {
            $group = $this->create($groupName);
        }

        return $group;
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return Group[]
     */
    public function getGroups(int $page, int $perPage): array
    {
        /** @var GroupRepository $groupRepository */
        $groupRepository = $this->entityManager->getRepository(Group::class);
        return $groupRepository->getGroups($page, $perPage);
    }

    /**
     * @param int $id
     * @return Group
     */
    public function getGroupById(int $id): Group
    {
        $userRepository = $this->entityManager->getRepository(Group::class);
        return $userRepository->findOneBy(['id' => $id]);
    }

    /**
     * @param int $groupId
     * @return bool
     */
    public function deleteGroup(int $groupId): bool
    {
        /** @var GroupRepository $groupRepository */
        $groupRepository = $this->entityManager->getRepository(Group::class);
        /** @var Group $group */
        $group = $groupRepository->find($groupId);
        if ($group === null) {
            return false;
        }
        $this->entityManager->remove($group);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param int $groupId
     * @param string $name
     * @return bool
     */
    public function updateGroup(int $groupId, string $name): bool
    {
        /** @var GroupRepository $groupRepository */
        $groupRepository = $this->entityManager->getRepository(Group::class);
        /** @var Group $group */
        $group = $groupRepository->find($groupId);
        if ($group === null) {
            return false;
        }
        $group->setName($name);
        $this->entityManager->flush();

        return true;
    }
}
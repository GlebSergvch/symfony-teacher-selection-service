<?php

namespace App\Manager;

use App\Entity\Group;
use App\Entity\Skill;
use App\Entity\User;
use App\Enum\UserRole;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function create(string $login, UserRole $role): User
    {
        $user = new User();
        $user->setLogin($login);
        $user->setRole($role->value);
        $user->setCreatedAt();
        $user->setUpdatedAt();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function updateUserWithUserProfile(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function addSkill(User $teacher, Skill $skill): void
    {
        $teacher->addSkill($skill);
        $this->entityManager->flush();
    }

    public function addGroup(User $student, Group $group): void
    {
        $student->addGroup($group);
        $this->entityManager->flush();
    }
}
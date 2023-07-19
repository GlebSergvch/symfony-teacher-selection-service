<?php

namespace App\Manager;

use App\Entity\Group;
use App\Entity\Skill;
use App\Entity\User;
use App\Enum\UserRole;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

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
//        $this->entityManager->flush();

        return $user;
    }

    public function flushUserWithUserProfile(User $user)
    {
//        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function persistUser(User $user)
    {
        $this->entityManager->persist($user);
    }

    public function flushUser()
    {
        $this->entityManager->flush();
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

    public function findUsersByLogin(string $name): array
    {
        return $this->entityManager->getRepository(User::class)->findBy(['login' => $name]);
    }

    public function findUsersByCriteria(string $login)
    {
        $criteria = Criteria::create();
        $criteria->andWhere(Criteria::expr()?->eq('login', $login));
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository(User::class);

        return $repository->matching($criteria)->toArray();
    }
}
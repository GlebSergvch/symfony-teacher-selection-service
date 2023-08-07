<?php

namespace App\Manager;

use App\Entity\Group;
use App\Entity\Skill;
use App\Entity\StudentGroup;
use App\Entity\TeacherSkill;
use App\Entity\User;
use App\Enum\UserRole;
use App\Repository\UserRepository;
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
        $user->setRole($role);
        $user->setCreatedAt();
        $user->setUpdatedAt();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

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

    public function addTeacherSkill(User $teacher, TeacherSkill $teacherSkill): void
    {
        $teacher->addTeacherSkill($teacherSkill);
        $this->entityManager->flush();
    }

    public function addStudentGroup(User $student, StudentGroup $group): void
    {
        $student->addStudentGroup($group);
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

    /**
     * @return User[]
     */
    public function getUsers(int $page, int $perPage): array
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
//        var_dump($userRepository->getUsers($page, $perPage)); die();
        return $userRepository->getUsers($page, $perPage);
    }

    public function getUsersByLogin(int $page, int $perPage, string $login): array
    {
        $userRepository = $this->entityManager->getRepository(User::class);
//        var_dump($userRepository->findByLogin($page, $perPage, $login)); die();
        return $userRepository->findByLogin($page, $perPage, $login);
    }

    public function saveUser(string $login, string $role): ?int
    {
        $user = new User();
        $user->setLogin($login);
        $user->setRole(UserRole::from($role));
        $user->setCreatedAt();
        $user->setUpdatedAt();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user->getId();
    }

    public function updateUser(int $userId, string $login): bool
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var User $user */
        $user = $userRepository->find($userId);
        if ($user === null) {
            return false;
        }
        $user->setLogin($login);
        $this->entityManager->flush();

        return true;
    }

    public function deleteUser(int $userId): bool
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        /** @var User $user */
        $user = $userRepository->find($userId);
        if ($user === null) {
            return false;
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return true;
    }

//    public function saveUser(string $login): ?int
//    {
//        $user = new User();
//        $user->setLogin($login);
//        $this->entityManager->persist($user);
//        $this->entityManager->flush();
//
//        return $user->getId();
//    }
}
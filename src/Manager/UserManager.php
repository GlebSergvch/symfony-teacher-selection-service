<?php

namespace App\Manager;

use App\Dto\ManageUserDTO;
use App\Entity\Group;
use App\Entity\Skill;
use App\Entity\StudentGroup;
use App\Entity\TeacherSkill;
use App\Entity\User;
use App\Enum\UserRole;
use App\Enum\UserStatus;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param string $login
     * @param UserRole $role
     * @return User
     */
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

    /**
     * @param User $user
     * @return User
     */
    public function flushUserWithUserProfile(User $user)
    {
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param User $user
     */
    public function persistUser(User $user): void
    {
        $this->entityManager->persist($user);
    }

    public function flushUser(): void
    {
        $this->entityManager->flush();
    }

    /**
     * @param User $teacher
     * @param TeacherSkill $teacherSkill
     */
    public function addTeacherSkill(User $teacher, TeacherSkill $teacherSkill): void
    {
        $teacher->addTeacherSkill($teacherSkill);
        $this->entityManager->flush();
    }

    /**
     * @param User $student
     * @param StudentGroup $group
     */
    public function addStudentGroup(User $student, StudentGroup $group): void
    {
        $student->addStudentGroup($group);
        $this->entityManager->flush();
    }

    /**
     * @param string $name
     * @return User[]
     */
    public function findUsersByLogin(string $name): array
    {
        return $this->entityManager->getRepository(User::class)->findBy(['login' => $name]);
    }

    /**
     * @param string $login
     * @return User[]
     */
    public function findUsersByCriteria(string $login)
    {
        $criteria = Criteria::create();
        $criteria->andWhere(Criteria::expr()?->eq('login', $login));
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository(User::class);

        return $repository->matching($criteria)->toArray();
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return User[]
     */
    public function getUsers(int $page, int $perPage): array
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        return $userRepository->getUsers($page, $perPage);
    }

    /**
     * @param string $login
     * @param int $page
     * @param int $perPage
     * @return User[]
     */
    public function getUsersByLogin(string $login, int $page, int $perPage): array
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        return $userRepository->findByLogin($page, $perPage, $login);
    }

    /**
     * @param array $data
     * @param int $page
     * @param int $perPage
     * @return User[]
     */
    public function getUsersByUserProfile(array $data, int $page, int $perPage): array
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        return $userRepository->findByUserProfile($page, $perPage, $data);
    }

    /**
     * @param int $id
     * @return User
     */
    public function getUserById(int $id)
    {
        $userRepository = $this->entityManager->getRepository(User::class);
        return $userRepository->findOneBy(['id' => $id]);
    }

    /**
     * @param string $login
     * @param string $role
     * @return int|null
     */
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

    /**
     * @param int $userId
     * @param string $login
     * @return bool
     */
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

    /**
     * @param int $userId
     * @return bool
     */
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

    public function saveUserFromDTO(User $user, ManageUserDTO $manageUserDTO): ?int
    {
        $user->setLogin($manageUserDTO->login);
        $user->setPassword($manageUserDTO->password);
        $user->setRole($manageUserDTO->role);
        $user->setStatus(UserStatus::ACTIVE);
        $user->setCreatedAt();
        $user->setUpdatedAt();
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user->getId();
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
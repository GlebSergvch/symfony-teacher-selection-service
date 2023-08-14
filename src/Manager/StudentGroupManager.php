<?php

namespace App\Manager;

use App\Entity\Group;
use App\Entity\Skill;
use App\Entity\StudentGroup;
use App\Entity\TeacherSkill;
use App\Entity\User;
use App\Repository\StudentGroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class StudentGroupManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param User $student
     * @param Group $group
     * @return StudentGroup
     */
    public function create(User $student, Group $group): StudentGroup
    {
        $studentGroup = new StudentGroup();
        $studentGroup->setStudent($student);
        $studentGroup->setGroup($group);
        $studentGroup->setCreatedAt();
        $studentGroup->setUpdatedAt();
        $this->entityManager->persist($studentGroup);
        $this->entityManager->flush();

        return $studentGroup;
    }

    /**
     * @param User $user
     * @param Group $group
     * @return StudentGroup
     */
    public function addStudentGroup(User $user, Group $group): StudentGroup
    {
        $studentGroup = new StudentGroup();
        $studentGroup->setStudent($user);
        $studentGroup->setGroup($group);
        $studentGroup->setUpdatedAt();
        $studentGroup->setCreatedAt();
        $this->entityManager->persist($studentGroup);

        $this->entityManager->flush();

        return $studentGroup;
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return StudentGroup[]
     */
    public function getStudentGroup(int $page, int $perPage): array
    {
        /** @var StudentGroupRepository $studentGroupRepository */
        $studentGroupRepository = $this->entityManager->getRepository(StudentGroup::class);
        return $studentGroupRepository->getStudentGroups($page, $perPage);
    }

    /**
     * @param int $studentId
     * @param int $groupId
     * @return bool
     */
    public function deleteStudentGroup(int $studentId, int $groupId): bool
    {
        /** @var StudentGroupRepository $studentGroupRepository */
        $studentGroupRepository = $this->entityManager->getRepository(StudentGroup::class);
        /** @var StudentGroup $StudentGroup */
        $StudentGroup = $studentGroupRepository->findOneBy(['student_id' => $studentId, 'group_id' => $groupId]);
        if ($StudentGroup === null) {
            return false;
        }
        $this->entityManager->remove($StudentGroup);
        $this->entityManager->flush();

        return true;
    }

}
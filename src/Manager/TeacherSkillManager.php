<?php

namespace App\Manager;

use App\Entity\Skill;
use App\Entity\TeacherSkill;
use App\Entity\User;
use App\Repository\TeacherSkillRepository;
use Doctrine\ORM\EntityManagerInterface;

class TeacherSkillManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param User $teacher
     * @param Skill $skill
     * @return TeacherSkill
     */
    public function create(User $teacher, Skill $skill): TeacherSkill
    {
        $teacherSkill = new TeacherSkill();
        $teacherSkill->setTeacher($teacher);
        $teacherSkill->setSkill($skill);
        $teacherSkill->setCreatedAt();
        $teacherSkill->setUpdatedAt();
        $this->entityManager->persist($teacherSkill);
        $this->entityManager->flush();

        return $teacherSkill;
    }

    /**
     * @param User $user
     * @param Skill $skill
     * @return TeacherSkill
     */
    public function addTeacherSkill(User $user, Skill $skill)
    {
        $teacherSkill = new TeacherSkill();
        $teacherSkill->setTeacher($user);
        $teacherSkill->setSkill($skill);
        $teacherSkill->setUpdatedAt();
        $teacherSkill->setCreatedAt();

        $this->entityManager->persist($teacherSkill);
        $this->entityManager->flush();

        return $teacherSkill;
    }

    /**
     * @return TeacherSkill[]
     */
    public function getTeacherSkill(int $page, int $perPage): array
    {
        /** @var TeacherSkillRepository $teacherSkillRepository */
        $teacherSkillRepository = $this->entityManager->getRepository(TeacherSkill::class);
        return $teacherSkillRepository->getTeacherSkill($page, $perPage);
    }

    /**
     * @param int $teacherId
     * @param int $skillId
     * @return bool
     */
    public function deleteTeacherSkill(int $teacherId, int $skillId)
    {
        /** @var TeacherSkillRepository $teacherSkillRepository */
        $teacherSkillRepository = $this->entityManager->getRepository(TeacherSkill::class);
        /** @var TeacherSkill $teacherSkill */
        $teacherSkill = $teacherSkillRepository->findOneBy(['teacher_id' => $teacherId, 'skill_id' => $skillId]);
        if ($teacherSkill === null) {
            return false;
        }
        $this->entityManager->remove($teacherSkill);
        $this->entityManager->flush();

        return true;
    }

}
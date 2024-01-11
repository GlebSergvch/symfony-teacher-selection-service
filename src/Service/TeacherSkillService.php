<?php

namespace App\Service;

use App\Enum\UserRole;
use App\Manager\SkillManager;
use App\Manager\TeacherSkillManager;
use App\Manager\UserManager;

class TeacherSkillService
{
    public function __construct(
        private TeacherSkillManager $teacherSkillManager,
        private SkillManager $skillManager,
        private UserManager $userManager
    )
    {
    }

    public function addTeachersSkills(array $teachers, array $skills)
    {
        $users = [];
        foreach ($teachers as $teacher) {
            $user = $this->userManager->getUserByLoginRole($teacher,UserRole::TEACHER);
            if ($user) {
                foreach ($skills as $skillName) {
                    $skill = $this->skillManager->findOrCreateSkill($skillName);

                    $teacherSkill = $this->teacherSkillManager->findOrCreateTeacherSkill($user, $skill);
                }
                $users[] = $user;
            }
        }
        return $users;
    }
}
<?php

namespace App\Service;

use App\Dto\UserProfileDto;
use App\Entity\StudentGroup;
use App\Entity\TeacherSkill;
use App\Entity\User;
use App\Enum\UserRole;
use App\Manager\GroupManager;
use App\Manager\SkillManager;
use App\Manager\StudentGroupManager;
use App\Manager\TeacherSkillManager;
use App\Manager\UserManager;
use App\Manager\UserProfileManager;

class UserBuilderService
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly SkillManager $skillManager,
        private readonly TeacherSkillManager $teacherSkillManager,
        private readonly StudentGroupManager $studentGroupManager,
        private readonly GroupManager $groupManager,
        private readonly UserProfileManager $userProfileManager
    ) {
    }

    public function createTeacherWithSkills(string $login, array $skills): User
    {
        $user = $this->userManager->create($login, UserRole::TEACHER);
        foreach ($skills as $skillName) {

            $skill = $this->skillManager->findOrCreateSkill($skillName);

            $teacherSkill = $this->teacherSkillManager->create($user, $skill);
            $this->userManager->addTeacherSkill($user, $teacherSkill);
        }

        return $user;
    }

    public function createStudentWithGroup(string $login): User
    {
        $student = $this->userManager->create($login, UserRole::STUDENT);
        $group = $this->groupManager->findOrCreateGroup('PS1');
        $studentGroup = $this->studentGroupManager->create($student, $group);
        $this->userManager->addStudentGroup($student, $studentGroup);

        return $student;
    }

    public function createUserWithUserProfile(string $login, UserProfileDto $userProfileDto): User
    {
        $userProfile = $this->userProfileManager->create($userProfileDto);
        $user = $this->userManager->create($login, UserRole::STUDENT);
        $user->setUserProfile($userProfile);
        $this->userManager->flushUserWithUserProfile($user);

        return $user;
    }
}
<?php

namespace App\Services;

use App\Dto\UserProfileDto;
use App\Entity\TeacherSkill;
use App\Entity\User;
use App\Enum\UserRole;
use App\Manager\GroupManager;
use App\Manager\SkillManager;
use App\Manager\TeacherSkillManager;
use App\Manager\UserManager;
use App\Manager\UserProfileManager;

class UserBuilderService
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly SkillManager $skillManager,
        private readonly TeacherSkillManager $teacherSkillManager,
        private readonly GroupManager $groupManager,
        private readonly UserProfileManager $userProfileManager
    ) {
    }

    public function createTeacherWithSkills(string $login, array $skills): User
    {
        $user = $this->userManager->create($login, UserRole::TEACHER);

        foreach ($skills as $skill) {
            //TODO заменить create на findOrCreate
            $skill = $this->teacherSkillManager->create($skill);
            $this->userManager->addSkill($user, $skill);
        }

        return $user;
    }

    public function createStudentWithGroup(string $login): User
    {
        $user = $this->userManager->create($login, UserRole::STUDENT);
        $group = $this->groupManager->create('PS1');
        $this->userManager->addGroup($user, $group);

        return $user;
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
<?php

namespace App\Controller;

use App\Dto\UserProfileDto;
use App\Entity\Group;
use App\Entity\Skill;
use App\Entity\User;
use App\Enum\UserProfileGender;
use App\Manager\GroupManager;
use App\Manager\SkillManager;
use App\Manager\UserManager;
use App\Services\UserBuilderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class WorldController extends AbstractController
{
    public function __construct(
        private readonly UserBuilderService $userBuilderService,
        private readonly UserManager $userManager,
        private readonly SkillManager $skillManager,
        private readonly GroupManager $groupManager
    )
    {

    }

    public function hello(): Response
    {
//        $skills = [
//            'philosophy',
//            'geography',
//            'physic'
//        ];
//        $user = $this->userBuilderService->createTeacherWithSkills('Tech Mr. Johns', $skills);
//        return $this->json($user->toArray());


//        $user = $this->userBuilderService->createStudentWithGroup('STUDENT');
//        return $this->json($user->toArray());

//        $userProfileDto = new UserProfileDto();
//        $userProfileDto->firstname = 'John';
//        $userProfileDto->middlename = 'Doe';
//        $userProfileDto->lastname = 'James';
//        $userProfileDto->gender = UserProfileGender::MALE;
//
//        $user = $this->userBuilderService->createUserWithUserProfile('STUDENT', $userProfileDto);
//        return $this->json($user->toArray());

//        $users = $this->userManager->findUsersByLogin('alex');
//        return $this->json(array_map(static fn(User $user) => $user->toArray(), $users));

//        $skills = $this->skillManager->findSkillByName('skill1');
//        return $this->json(array_map(static fn(Skill $skill) => $skill->toArray(), $skills));

        $groups = $this->groupManager->findGroupByName('PS1');
        return $this->json(array_map(static fn(Group $group) => $group->toArray(), $groups));
    }
}

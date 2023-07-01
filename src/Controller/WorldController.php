<?php

namespace App\Controller;

use App\Dto\UserProfileDto;
use App\Enum\UserProfileGender;
use App\Manager\UserManager;
use App\Services\UserBuilderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class WorldController extends AbstractController
{
    public function __construct(private readonly UserBuilderService $userBuilderService)
    {

    }

    public function hello(): Response
    {
//        $skills = [
//            'skill1',
//            'skill2',
//            'skill3'
//        ];
//        $user = $this->userBuilderService->createTeacherWithSkills('alex', $skills);
//        return $this->json($user->toArray());


//        $user = $this->userBuilderService->createStudentWithGroup('STUDENT');
//        return $this->json($user->toArray());

        $userProfileDto = new UserProfileDto();
        $userProfileDto->firstname = 'John';
        $userProfileDto->middlename = 'Doe';
        $userProfileDto->lastname = 'James';
        $userProfileDto->gender = UserProfileGender::MALE;

        $user = $this->userBuilderService->createUserWithUserProfile('STUDENT', $userProfileDto);
        return $this->json($user->toArray());
    }
}

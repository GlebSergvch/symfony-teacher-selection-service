<?php

namespace App\Manager;

use App\Dto\UserProfileDto;
use App\Entity\Group;
use App\Entity\Skill;
use App\Entity\UserProfile;
use Doctrine\ORM\EntityManagerInterface;

class UserProfileManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }


    public function create(UserProfileDto $userProfileDto): UserProfile
    {
        $userProfile = new UserProfile();
        $userProfile->setFirstname($userProfileDto->firstname);
        $userProfile->setMiddlename($userProfileDto->middlename);
        $userProfile->setLastname($userProfileDto->lastname);
        $userProfile->setGender($userProfileDto->gender);
        $userProfile->setCreatedAt();
        $userProfile->setUpdatedAt();

        $this->entityManager->persist($userProfile);
//        $this->entityManager->flush();
        return $userProfile;
    }
}
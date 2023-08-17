<?php

namespace App\Manager;

use App\Dto\ManageUserProfileDTO;
use App\Entity\Group;
use App\Entity\Skill;
use App\Entity\UserProfile;
use Doctrine\ORM\EntityManagerInterface;

class UserProfileManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param ManageUserProfileDTO $userProfileDto
     * @return UserProfile
     */
    public function create(ManageUserProfileDTO $userProfileDto): UserProfile
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

    /**
     * @param int $id
     * @return UserProfile
     */
    public function getUserProfileById(int $id)
    {
        $userProfileRepository = $this->entityManager->getRepository(UserProfile::class);
        return $userProfileRepository->findOneBy(['id' => $id]);
    }

    public function saveUserFromDTO(UserProfile $userProfile, ManageUserProfileDTO $manageUserProfileDTO): ?int
    {
        $userProfile->setFirstname($manageUserProfileDTO->firstname);
        $userProfile->setLastname($manageUserProfileDTO->lastname);
        $userProfile->setMiddlename($manageUserProfileDTO->middlename);
        $userProfile->setGender($manageUserProfileDTO->gender);
        $userProfile->setAge($manageUserProfileDTO->age);
        $this->entityManager->persist($userProfile);
        $this->entityManager->flush();

        return $userProfile->getId();
    }
}
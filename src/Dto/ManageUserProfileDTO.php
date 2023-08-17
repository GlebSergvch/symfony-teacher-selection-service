<?php

namespace App\Dto;

use App\Entity\UserProfile;
use App\Enum\UserProfileGender;
use Symfony\Component\Validator\Constraints as Assert;

class ManageUserProfileDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 32)]
        public string $firstname = '',

        #[Assert\NotBlank]
        #[Assert\Length(max: 32)]
        public string $middlename = '',

        #[Assert\NotBlank]
        #[Assert\Length(max: 32)]
        public string $lastname = '',

        #[Assert\NotBlank]
        public UserProfileGender|null $gender = null,

        #[Assert\NotBlank]
        #[Assert\GreaterThan(18)]
        public int $age = 0,
    ) {
    }

    public static function fromEntity(UserProfile $userProfile): self
    {
        return new self(...[
            'firstname' => $userProfile->getFirstname(),
            'middlename' => $userProfile->getMiddlename(),
            'lastname' => $userProfile->getLastname()
        ]);
    }
}
<?php

namespace App\Dto;

use App\Enum\UserRole;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

class ManageUserDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 32)]
        public string $login = '',

        #[Assert\NotBlank]
        public UserRole|null $role = null,

        #[Assert\NotBlank]
        #[Assert\Length(max: 32)]
        public string|null $password = '',

        public bool $isActive = false,

        #[Assert\Type('array')]
        public array $followers = [],
    ) {
    }

    public static function fromEntity(User $user): self
    {
        return new self(...[
            'login' => $user->getLogin(),
            'password' => $user->getPassword()
        ]);
    }
}
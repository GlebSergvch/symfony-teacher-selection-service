<?php

namespace App\Dto;

use App\Enum\UserRole;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

class ManageUserDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 32)]
        public string $login = '',

        #[Assert\Type('array')]
        public array $roles = [],

        #[Assert\NotBlank]
        #[Assert\Length(max: 32)]
        public string|null $password = '',

        public bool $isActive = false,
    ) {
    }

    public static function fromEntity(User $user): self
    {
        return new self(...[
            'login' => $user->getLogin(),
            'roles' => $user->getRoles(),
            'password' => $user->getPassword()
        ]);
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            login: $request->request->get('login') ?? $request->query->get('login'),
            roles: $request->request->get('roles') ?? $request->query->get('roles') ?? [],
            password: $request->request->get('password') ?? $request->query->get('password'),
            isActive: $request->request->get('isActive') ?? $request->query->get('isActive'),
        );
    }
}
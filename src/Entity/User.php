<?php

namespace App\Entity;

use App\Enum\UserRole;
use App\Enum\UserStatus;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\UniqueConstraint;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Table(name: '`user`', indexes: [])]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueConstraint(name: "user__login__uniq__idx", columns: ["login"])]
#[UniqueConstraint(name: "user__password__uniq__idx", columns: ["password"])]
#[UniqueConstraint(name: "user__user_profile_id__uniq__idx", columns: ["user_profile_id"])]
#[UniqueConstraint(name: "user__phone__uniq__idx", columns: ["phone"])]
#[UniqueConstraint(name: "user__email__uniq__idx", columns: ["email"])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const EMAIL_NOTIFICATION = 'email';
    public const SMS_NOTIFICATION = 'sms';

    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 32, unique: true, nullable: false)]
    private string $login;

    #[ORM\Column(type: 'string', length: 120, unique: true, nullable: false)]
    private string $password;

    #[ORM\Column(type: 'string', length: 32, unique: true, nullable: true)]
    private ?string $token = null;

    #[ORM\Column(type: 'json', length: 1024, nullable: false)]
    private array $roles = [];

    #[ORM\Column(type: 'string', length: 32, nullable: false, enumType: UserStatus::class)]
    private UserStatus $status;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private DateTime $updatedAt;

    #[ORM\Column(type: 'string', length: 11, unique: true, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(type: 'string', length: 128, unique: true, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $preferred = null;

    #[ORM\OneToOne(targetEntity: "UserProfile")]
    #[ORM\JoinColumn(name: 'user_profile_id', referencedColumnName: 'id')]
    private UserProfile|null $userProfile = null;

    #[ORM\OneToMany(mappedBy: 'teacher', targetEntity: TeacherSkill::class)]
    private Collection $teacherSkills;

    #[ORM\OneToMany(mappedBy: 'student', targetEntity: StudentGroup::class)]
    private Collection $studentGroups;

    public function __construct()
    {
        $this->teacherSkills = new ArrayCollection();
        $this->studentGroups = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUsername(): string
    {
        return $this->login;
    }

    public function getUserIdentifier(): string
    {
        return $this->login;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getStatus(): UserStatus
    {
        return $this->status;
    }

    public function setStatus(UserStatus $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    public function setCreatedAt(): void {
        $this->createdAt = new DateTime();
    }

    public function getUpdatedAt(): DateTime {
        return $this->updatedAt;
    }

    public function setUpdatedAt(): void {
        $this->updatedAt = new DateTime();
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPreferred(): ?string
    {
        return $this->preferred;
    }

    public function setPreferred(?string $preferred): void
    {
        $this->preferred = $preferred;
    }

    public function getuserProfile()
    {
        return $this->userProfile;
    }

    public function setUserProfile(UserProfile $userProfile)
    {
        $this->userProfile = $userProfile;
    }

    public function addUserProfile(User $user, UserProfile $userProfile): void
    {
        $user->setUserProfile($userProfile);
    }

    public function addTeacherSkill(TeacherSkill $teacherSkill): void
    {
        if (!$this->teacherSkills->contains($teacherSkill)) {
            $this->teacherSkills->add($teacherSkill);
        }
    }

    public function addStudentGroup(StudentGroup $studentGroup): void
    {
        if (!$this->studentGroups->contains($studentGroup)) {
            $this->studentGroups->add($studentGroup);
        }
    }

    #[ArrayShape([
        'id' => 'int|null',
        'login' => 'string',
        'role' => 'string',
        'password' => 'string',
        'createdAt' => 'string',
        'updatedAt' => 'string',
        'skills' =>  ['string'],
        'groups' =>  [],
        'userProfile' => []
    ])]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'role' => $this->roles,
            'password' => $this->password,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
            'skills' => array_map(static fn(TeacherSkill $teacherSkill) => $teacherSkill->getSkillName(), $this->teacherSkills->toArray()),
//            'skills' => array_map(static fn(TeacherSkill $teacherSkill) => $teacherSkill->skill->toArray(), $this->teacherSkill->skill->toArray()),
            'groups' => array_map(static fn(StudentGroup $studentGroup) => $studentGroup->getGroupName(), $this->studentGroups->toArray()),
//            'groups' => array_map(static fn(Group $group) => $group->toArray(), $this->groups->toArray()),
            'userProfile' => $this->userProfile?->toArray(),
        ];
    }
}

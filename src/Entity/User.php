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

#[ORM\Table(name: '`user`', indexes: [])]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueConstraint(name: "user__login__uniq__idx", columns: ["login"])]
#[UniqueConstraint(name: "user__user_profile_id__uniq__idx", columns: ["user_profile_id"])]
class User
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 32, nullable: false)]
    private string $login;

    #[ORM\Column(type: 'string', length: 32, nullable: false)]
    private string $password;

    #[ORM\Column(type: 'string', length: 32, nullable: false, enumType: UserRole::class)]
    private UserRole $role;

    #[ORM\Column(type: 'string', length: 32, nullable: false, enumType: UserStatus::class)]
    private UserStatus $status;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private DateTime $updatedAt;

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

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function setRole(UserRole $role): void
    {
        $this->role = $role;
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
            'role' => $this->role,
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

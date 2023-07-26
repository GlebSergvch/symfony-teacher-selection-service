<?php

namespace App\Entity;

use App\Enum\UserRole;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\UniqueConstraint;
use JetBrains\PhpStorm\ArrayShape;

#[ORM\Table(name: '`user`')]
#[ORM\Entity]
//#[UniqueConstraint(name: "user__user_profile_id__uniq__idx", columns: ["user_profile_id"])]
class User
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 32, nullable: false)]
    private string $login;

    #[ORM\Column(type: 'string', length: 32, nullable: false, enumType: UserRole::class)]
    private UserRole $role;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private DateTime $updatedAt;

    #[ORM\OneToOne(targetEntity: "UserProfile")]
    #[ORM\JoinColumn(name: 'user_profile_id', referencedColumnName: 'id')]
    private UserProfile|null $userProfile = null;

//    #[ORM\ManyToMany(targetEntity: 'Skill')]
//    #[ORM\JoinTable(name: 'teacher_skill')]
//    #[ORM\JoinColumn(name: 'teacher_id', referencedColumnName: 'id')]
//    #[ORM\InverseJoinColumn(name: 'skill_id', referencedColumnName: 'id')]
//    private Collection $skills;

    #[ORM\ManyToOne(targetEntity: TeacherSkill::class)]
    #[JoinColumn(name: 'id', referencedColumnName: 'teacher_id', nullable: true)]
    private ?TeacherSkill $teacherSkill = null;

    #[ORM\ManyToMany(targetEntity: 'Group')]
    #[ORM\JoinTable(name: 'student_group')]
    #[ORM\JoinColumn(name: 'student_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private Collection $groups;

    public function __construct()
    {
//        $this->userProfile = new UserProfile();
        $this->skills = new ArrayCollection();
        $this->groups = new ArrayCollection();
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

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function setRole(UserRole $role): void
    {
        $this->role = $role;
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

//    public function addSkill(Skill $skill): void
//    {
//        if (!$this->skills->contains($skill)) {
//            $this->skills->add($skill);
//        }
//    }

    public function addTeacherSkill(Skill $skill): void
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
        }
    }

    public function addGroup(Group $group): void
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }
    }

    #[ArrayShape([
        'id' => 'int|null',
        'login' => 'string',
        'role' => 'string',
        'createdAt' => 'string',
        'updatedAt' => 'string',
        'skills' =>  [],
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
//            'skills' => array_map(static fn(Skill $skill) => $skill->toArray(), $this->skills->toArray()),
//            'skills' => array_map(static fn(TeacherSkill $teacherSkill) => $teacherSkill->skill->toArray(), $this->teacherSkill->skill->toArray()),
            'groups' => array_map(static fn(Group $group) => $group->toArray(), $this->groups->toArray()),
            'userProfile' => $this->userProfile?->toArray(),
        ];
    }
}

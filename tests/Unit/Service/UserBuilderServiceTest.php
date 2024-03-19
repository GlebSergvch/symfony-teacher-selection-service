<?php

namespace CodeceptionUnitTests\Service;

use App\Dto\ManageUserProfileDTO;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Enum\UserProfileGender;
use App\Enum\UserRole;
use App\Manager\GroupManager;
use App\Manager\SkillManager;
use App\Manager\StudentGroupManager;
use App\Manager\TeacherSkillManager;
use App\Manager\UserManager;
use App\Manager\UserProfileManager;
use App\Service\UserBuilderService;
use Codeception\Test\Unit;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Mockery\MockInterface;

class UserBuilderServiceTest extends Unit
{
    public function testCreateUserWithUserProfile(): void
    {
        // Mocking dependencies
        $userManagerMock = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $skillManagerMock = $this->getMockBuilder(SkillManager::class)->disableOriginalConstructor()->getMock();
        $teacherSkillManagerMock = $this->getMockBuilder(TeacherSkillManager::class)->disableOriginalConstructor()->getMock();
        $studentGroupManagerMock = $this->getMockBuilder(StudentGroupManager::class)->disableOriginalConstructor()->getMock();
        $groupManagerMock = $this->getMockBuilder(GroupManager::class)->disableOriginalConstructor()->getMock();
        $userProfileManagerMock = $this->getMockBuilder(UserProfileManager::class)->disableOriginalConstructor()->getMock();

        // Creating a new instance of UserBuilderService with mocked dependencies
        $userBuilderService = new UserBuilderService(
            $userManagerMock,
            $skillManagerMock,
            $teacherSkillManagerMock,
            $studentGroupManagerMock,
            $groupManagerMock,
            $userProfileManagerMock
        );

        // Creating a ManageUserProfileDTO instance
        $userProfileDto = new ManageUserProfileDTO();
        // Setting up properties of ManageUserProfileDTO as needed for the test

        // Mocking the UserProfileManager::create method

        $userProfileEntity = $this->getUserProfileMock();

        $userProfileEntity->expects($this->once())
            ->method('getId')
            ->willReturn(1);

        $userProfileEntity->expects($this->once())
            ->method('getFirstname')
            ->willReturn('John');

        $userProfileManagerMock->expects($this->once())
            ->method('create')
            ->with($userProfileDto)
            ->willReturn($userProfileEntity);

        // Creating a new user
        $login = 'test_user';
        $user = new User();
        $user->setId(1);
        $user->setLogin($login);
        $user->setRoles(['ROLE_USER']);

        // Mocking the UserManager::create method
        $userManagerMock->expects($this->once())
            ->method('create')
            ->with($login, UserRole::STUDENT)
            ->willReturn($user);

        // Mocking the UserManager::flushUserWithUserProfile method
        $userManagerMock->expects($this->once())
            ->method('flushUserWithUserProfile')
            ->with($user);

        // Calling the method under test
        $createdUser = $userBuilderService->createUserWithUserProfile($login, $userProfileDto);

//        dd($createdUser);

//        dd($createdUser->getuserProfile());

        // Assertions
        $this->assertInstanceOf(User::class, $createdUser);
        $this->assertEquals($login, $createdUser->getLogin());
        $this->assertEquals(1, $createdUser->getuserProfile()->getId());
        $this->assertEquals('John', $createdUser->getuserProfile()->getFirstname());
        $this->assertEquals('Doe', $createdUser->getuserProfile()->getMiddlename());
        $this->assertEquals('Smith', $createdUser->getuserProfile()->getLastname());
        $this->assertEquals(UserProfileGender::MALE, $createdUser->getuserProfile()->getGender());
        $this->assertEquals(30, $createdUser->getuserProfile()->getAge());
        $this->assertEquals('2022-01-01 12:00:00', $createdUser->getuserProfile()->getCreatedAt()->format('Y-m-d H:i:s'));
        $this->assertEquals('2022-01-01 12:00:00', $createdUser->getuserProfile()->getUpdatedAt()->format('Y-m-d H:i:s'));
        $this->assertEquals(['ROLE_USER'], $createdUser->getRoles());
        // You may add more assertions here depending on your needs
    }

    public function getUserProfileMock()
    {
        $userProfileMock = $this->getMockBuilder(UserProfile::class)
            ->disableOriginalConstructor()
            ->getMock();

// Задаем ожидаемое поведение для необходимых методов
//        $userProfileMock->expects($this->once())
//            ->method('getId')
//            ->willReturn(1);

        $userProfileMock->expects($this->once())
            ->method('getFirstname')
            ->willReturn('John');

        $userProfileMock->expects($this->once())
            ->method('getMiddlename')
            ->willReturn('Doe');

        $userProfileMock->expects($this->once())
            ->method('getLastname')
            ->willReturn('Smith');

        $userProfileMock->expects($this->once())
            ->method('getGender')
            ->willReturn(UserProfileGender::MALE);

        $userProfileMock->expects($this->once())
            ->method('getAge')
            ->willReturn(30);

        $userProfileMock->expects($this->once())
            ->method('getCreatedAt')
            ->willReturn(new DateTime('2022-01-01 12:00:00'));

        $userProfileMock->expects($this->once())
            ->method('getUpdatedAt')
            ->willReturn(new DateTime('2022-01-01 12:00:00'));

// Используем макет UserProfile в тесте
        $userProfileEntity = $userProfileMock;
        return $userProfileMock;
    }
}
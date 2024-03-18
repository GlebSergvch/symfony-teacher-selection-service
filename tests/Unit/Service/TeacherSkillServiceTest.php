<?php

namespace CodeceptionUnitTests\Service;

use App\Entity\Skill;
use App\Entity\TeacherSkill;
use App\Entity\User;
use App\Manager\SkillManager;
use App\Manager\TeacherSkillManager;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Mockery;
use Mockery\MockInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TeacherSkillServiceTest extends Unit
{
    /** @var EntityManagerInterface|MockInterface */
    private static $entityManager;

    /** @var UserPasswordHasherInterface|MockInterface */
//    private static $passwordHasher;
    private const CORRECT_TEACHER = 1;
    private const CORRECT_SKILL = 2;
    private const INCORRECT_TEACHER = 3;
    private const INCORRECT_SKILL = 4;

    public static function setUpBeforeClass(): void
    {
        /** @var MockInterface|EntityRepository $repository */
        $repository = Mockery::mock(EntityRepository::class);
        $repository->shouldReceive('findOneBy')->with(['teacher_id' => self::CORRECT_TEACHER, 'skill_id' => self::CORRECT_SKILL])->andReturn(new TeacherSkill());
        $repository->shouldReceive('findOneBy')->with(['teacher_id' => self::INCORRECT_TEACHER, 'skill_id' => self::CORRECT_SKILL])->andReturn(null);
        $repository->shouldReceive('findOneBy')->with(['teacher_id' => self::CORRECT_TEACHER, 'skill_id' => self::INCORRECT_SKILL])->andReturn(null);
        $repository->shouldReceive('findOneBy')->with(['teacher_id' => self::INCORRECT_TEACHER, 'skill_id' => self::INCORRECT_SKILL])->andReturn(null);
        $repository->shouldReceive('findOneBy')->with(self::CORRECT_TEACHER)->andReturn(new User());
        $repository->shouldReceive('findOneBy')->with(self::CORRECT_SKILL)->andReturn(new Skill());
        $repository->shouldReceive('findOneBy')->with(self::INCORRECT_TEACHER)->andReturn(null);
        $repository->shouldReceive('findOneBy')->with(self::INCORRECT_SKILL)->andReturn(null);
        /** @var MockInterface|EntityManagerInterface $repository */
        self::$entityManager = Mockery::mock(EntityManagerInterface::class);
        self::$entityManager->shouldReceive('getRepository')->with(TeacherSkill::class)->andReturn($repository);
        self::$entityManager->shouldReceive('remove')->with(Mockery::type(TeacherSkill::class));
        self::$entityManager->shouldReceive('persist');
        self::$entityManager->shouldReceive('flush');
    }

    public function subscribeDataProvider(): array
    {
        return [
            'both correct' => [self::CORRECT_TEACHER, self::CORRECT_SKILL, true],
            'teacher incorrect' => [self::INCORRECT_TEACHER, self::CORRECT_SKILL, false],
            'skill incorrect' => [self::INCORRECT_TEACHER, self::INCORRECT_SKILL, false],
            'both incorrect' => [self::INCORRECT_TEACHER, self::INCORRECT_SKILL, false],
        ];
    }

    public function getDataProvider(): array
    {
        return [
            'get correct' => [1, 1, true]
        ];
    }

    /**
     * @dataProvider subscribeDataProvider
     */
    public function testDeleteTeacherSkillCorrectResult(int $teacherId, int $skillId, bool $expected): void
    {
        $skillManager = new SkillManager(self::$entityManager);
        $teacherSkillManager = new TeacherSkillManager(self::$entityManager, $skillManager);

        $actual = $teacherSkillManager->deleteTeacherSkill($teacherId, $skillId);
        static::assertSame($expected, $actual, 'Subscribe should return correct result');
    }
}
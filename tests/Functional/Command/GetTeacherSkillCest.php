<?php

namespace IntegrationTests\Command;

use App\Entity\Skill;
use App\Entity\TeacherSkill;
use App\Entity\User;
use App\Manager\SkillManager;
use App\Manager\TeacherSkillManager;
use App\Repository\TeacherSkillRepository;
use App\Service\TeacherSkillService;
use App\Tests\Support\FunctionalTester;
use Codeception\Example;
use Doctrine\ORM\EntityManagerInterface;

class GetTeacherSkillCest
{
    private const TEACHER_ONE = 'teacher_one';
    private const TEACHER_TWO = 'teacher_two';
    private const TEACHER_THREE = 'teacher_three';
    private const SKILL_ONE = 'skill_one';
    private const SKILL_TWO = 'skill_two';
    private const SKILL_THREE = 'skill_three';
    private const SKILL_FOUR = 'skill_four';

    public function getTeachersSkillsDataProvider(): array
    {
        return [
            'teachers_skills' => [
                [
                    'teacher' => self::TEACHER_ONE,
                    'skill' => self::SKILL_ONE,
                ]
            ]
        ];
    }

    public function _before(FunctionalTester $I)
    {
        // Получаем EntityManager
        $em = $I->grabService('doctrine')->getManager();

        // Создаем преподавателя
        $teacherOne = $I->have(User::class, ['login' => self::TEACHER_ONE]);

        // Создаем навыки
        $skillOne = $I->have(Skill::class, ['name' => self::SKILL_ONE]);
        $skillTwo = $I->have(Skill::class, ['name' => self::SKILL_TWO]);

        $teacherSkillOne = $I->have(TeacherSkill::class, ['teacher' => $teacherOne, 'skill' => $skillOne]);
//        dd($teacherSkillOne);
    }

    /**
     * @dataProvider getTeachersSkillsDataProvider
     */
    public function testGetTeachersSkills(FunctionalTester $I, Example $example): void
    {
        $teacherSkillService = $I->grabService(TeacherSkillManager::class);
//        $teacherSkillService = $I->grabService(TeacherSkillRepository::class);
//        $teacherSkillService = $I->grabService(TeacherSkillService::class);
        $teacherSkills = $teacherSkillService->getTeacherSkill(1, 20);
        dd($teacherSkills);
        $I->assertSame($example['expected'], ['teachers_skills' => array_map(static fn(TeacherSkill $teacherSkill) => $teacherSkill->toArray(), $teacherSkills)]);
    }

}
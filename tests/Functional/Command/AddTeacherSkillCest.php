<?php

namespace IntegrationTests\Command;

use App\Entity\Skill;
use App\Entity\User;
use App\Manager\SkillManager;
use App\Manager\TeacherSkillManager;
use App\Manager\UserManager;
use App\Tests\Support\FunctionalTester;
use Codeception\Example;

class AddTeacherSkillCest
{

    private const COMMAND = 'teacher_skill:add';

    public function executeDataProvider(): array
    {
        return [
            'positive' => ['correctIds' => true, 'expected' => 'executing the command returned success', 'exitCode' => 0],
            'failure' => ['correctIds' => false, 'expected' => 'teacher or skill returned null', 'exitCode' => 2]
        ];
    }

    /**
     * @dataProvider executeDataProvider
     */
    public function testExecuteReturnsResult(FunctionalTester $I, Example $example): void
    {
        $teacher = $I->have(User::class);
        $skill = $I->have(Skill::class);
        $teacherId = $example['correctIds'] === true ? $teacher->getId() : 1000;
        $params = ['teacherId' => $teacherId, 'skillId' => $skill->getId()];
        $output = $I->runSymfonyConsoleCommand(self::COMMAND, $params, [], $example['exitCode']);
        $I->assertStringEndsWith($example['expected'], $output);
    }
}
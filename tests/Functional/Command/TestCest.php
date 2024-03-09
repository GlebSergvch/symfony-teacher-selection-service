<?php

namespace IntegrationTests\Command;

use App\Entity\User;
use App\Tests\Support\FunctionalTester;
use Codeception\Example;

class TestCest
{

    public function executeDataProvider(): array
    {
        return [
            'positive' => ['followersCount' => 20, 'expected' => 1, 'exitCode' => 0]
        ];
    }

    /**
     * @dataProvider executeDataProvider
     */
    public function testExecuteReturnsResult(FunctionalTester $I, Example $example): void
    {
        $author = $I->have(User::class);
        $output = $author->getId();
//        $inputs = $example['followersCount'] === null ? ["\n"] : [$example['followersCount']."\n"];
//        $output = $I->runSymfonyConsoleCommand(self::COMMAND, $params, $inputs, $example['exitCode']);
        $I->assertStringEndsWith($example['expected'], $output);
    }
}
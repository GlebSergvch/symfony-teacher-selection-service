<?php

namespace CodeceptionUnitTests\Entity;

use App\Entity\Skill;
use Codeception\Test\Unit;
use DateTime;
use Throwable;

class SkillTest extends Unit
{
    public function skillDataProvider(): array
    {
        $expectedPositive = [
            'id' => 1,
            'name' => 'PA',
            'createdAt' => (new DateTime())->format('Y-m-d h:i:s')
        ];
        $expectedNoCreatedAt = [
            'id' => 1,
            'name' => 'PA',
            'createdAt' => '',
        ];
        $positiveTweet = $this->makeSkill($expectedPositive);
        $noCreatedAtTweet = $this->makeSkill($expectedNoCreatedAt);
        return [
            'positive' => [
                $positiveTweet,
                $expectedPositive,
                0,
            ],
            'no createdAt' => [
                $noCreatedAtTweet,
                $expectedNoCreatedAt,
                0,
            ],
            'positive with delay' => [
                $positiveTweet,
                $expectedPositive,
                2,
            ]
        ];
    }

    public function incorrectDataProvider(): array
    {
        $incorrectData = [
            'id' => 'id',
            'name' => null,
            'createdAt' => '',
        ];
        $incorrectDataTweet = $this->makeSkill($incorrectData);
        return [
            'incorrect data' => [
                $incorrectDataTweet,
                false,
                0
            ]
        ];
    }

    /**
     * @dataProvider skillDataProvider
     */
    public function testToFeedSkillCorrectValues(Skill|string $skill, array|string $expected, ?int $delay = null): void
    {
        static::assertEquals($expected['id'], $skill->getId(), 'Skill::id should return correct id');
        static::assertEquals($expected['name'], $skill->getName(), 'Skill::name should return correct name');
        if ($expected['createdAt']) {
            static::assertEquals($expected['createdAt'], $skill->getCreatedAt()->format('Y-m-d h:i:s'), 'Skill::createdAt should return correct date');
        }
    }

    /**
     * @dataProvider skillDataProvider
     */
    public function testToSkillWithoutCreatedAt(Skill|string $skill, array|string $expected, ?int $delay = null): void
    {
        static::assertEquals($expected['id'], $skill->getId(), 'Tweet::toFeed should return correct id');
        static::assertEquals($expected['name'], $skill->getName(), 'Tweet::toFeed should return correct name');
    }

    /**
     * @dataProvider incorrectDataProvider
     */
    public function testToSkillIncorrectData($input, $expected, ?int $delay = null): void
    {
        static::assertEquals($input, $expected);
    }

    /**
     * @param array $data
     * @return Skill|bool
     */
    private function makeSkill(array $data): Skill|bool
    {
        try {
            $skill = new Skill();
            $skill->setId($data['id']);
            $skill->setName($data['name']);
            if ($data['createdAt']) {
                $skill->setCreatedAt();
            }

            return $skill;
        } catch (Throwable $exception) {
            return false;
        }
    }

    /**
     * @param Skill $skill
     * @param int|null $delay
     * @return Skill
     */
    private function setCreatedAtWithDelay(Skill $skill, ?int $delay = null): Skill
    {
        if ($delay !== null) {
            \sleep($delay);
            $skill->setCreatedAt();
        }

        return $skill;
    }
}
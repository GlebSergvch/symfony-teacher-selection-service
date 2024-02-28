<?php

namespace UnitTests\Entity;

use App\Entity\Skill;
use DateTime;
use PHPUnit\Framework\TestCase;

class SkillTest extends TestCase
{
    private const NOW_TIME = '@now';

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
        return [
            'positive' => [
                $positiveTweet,
                $expectedPositive,
                0,
            ],
            'no createdAt' => [
                $this->makeSkill($expectedNoCreatedAt),
                $expectedNoCreatedAt,
                0,
            ],
            'positive with delay' => [
                $positiveTweet,
                $expectedPositive,
                2,
            ],
        ];
    }

    /**
     * @dataProvider skillDataProvider
     */
    public function testToFeedSkillCorrectValues(Skill $skill, array $expected, ?int $delay = null): void
    {
        static::assertEquals($expected['id'], $skill->getId(), 'Tweet::toFeed should return correct id');
        static::assertEquals($expected['name'], $skill->getName(), 'Tweet::toFeed should return correct name');
        if ($expected['createdAt']) {
            static::assertEquals($expected['createdAt'], $skill->getCreatedAt()->format('Y-m-d h:i:s'), 'Tweet::toFeed should return correct name');
        }
    }

//    /**
//     * @dataProvider skillDataProvider
//     */
//    public function testToSkillWithoutCreatedAt(Skill $skill, array $expected, ?int $delay = null): void
//    {
//        static::assertEquals($expected['id'], $skill->getId(), 'Tweet::toFeed should return correct id');
//        static::assertEquals($expected['name'], $skill->getName(), 'Tweet::toFeed should return correct name');
//    }

    private function makeSkill(array $data): Skill
    {
        $skill = new Skill();
        $skill->setId($data['id']);
        $skill->setName($data['name']);
        if ($data['createdAt']) {
            $skill->setCreatedAt();
        }

        return $skill;
    }

    private function setCreatedAtWithDelay(Skill $skill, ?int $delay = null): Skill
    {
        if ($delay !== null) {
            \sleep($delay);
            $skill->setCreatedAt();
        }

        return $skill;
    }
}
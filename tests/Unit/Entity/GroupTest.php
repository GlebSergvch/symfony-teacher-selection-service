<?php

namespace CodeceptionUnitTests\Entity;

use App\Entity\Group;
use App\Entity\Skill;
use Codeception\Test\Unit;
use DateTime;
use Throwable;

class GroupTest extends Unit
{
    public function groupDataProvider(): array
    {
        $expectedPositiveGroup = [
            'id' => 1,
            'name' => 'FI-11',
            'minimumSize' => 10,
            'maximumSize' => 15,
            'createdAt' => (new DateTime())->format('Y-m-d h:i:s'),
            'updatedAt' => (new DateTime())->format('Y-m-d h:i:s')
        ];
        $expectedGroupNoCreatedAt = [
            'id' => 1,
            'name' => 'FI-11',
            'minimumSize' => 10,
            'maximumSize' => 15,
            'createdAt' => null,
            'updatedAt' => null
        ];
        $positiveGroup = $this->makeGroup($expectedPositiveGroup);
        $positiveNoCreatedAtGroup = $this->makeGroup($expectedGroupNoCreatedAt);

        return [
            'positive' => [
                $positiveGroup,
                $expectedPositiveGroup,
                0,
            ],
            'no createdAt' => [
                $positiveNoCreatedAtGroup,
                $expectedGroupNoCreatedAt,
                0,
            ]
        ];
    }

    public function groupIncorrectDataProvider(): array
    {
        $expectedIncorrectData = [
            'id' => 'id',
            'name' => 222,
            'minimumSize' => 'minimumSize',
            'maximumSize' => 'maximumSize',
            'createdAt' => '10-11',
            'updatedAt' => '10-12'
        ];
        $incorrectDataTweet = $this->makeGroup($expectedIncorrectData);
        return [
            'incorrect data' => [
                $incorrectDataTweet,
                false,
                0
            ]
        ];
    }

    /**
     * @dataProvider groupDataProvider
     */
    public function testToGroupCorrectValues(Group $group, array $expected, ?int $delay = null): void
    {
        static::assertEquals($expected['id'], $group->getId(), 'Group::id should return correct id');
        static::assertEquals($expected['name'], $group->getName(), 'Group::name should return correct name');
        static::assertEquals($expected['minimumSize'], $group->getMinimumSize(), 'Group::name should return correct name');
        static::assertEquals($expected['maximumSize'], $group->getMaximumSize(), 'Group::name should return correct name');
        if ($expected['createdAt']) {
            static::assertEquals($expected['createdAt'], $group->getCreatedAt()->format('Y-m-d h:i:s'), 'Group::createdAt should return correct date');
        }
        if ($expected['updatedAt']) {
            static::assertEquals($expected['createdAt'], $group->getCreatedAt()->format('Y-m-d h:i:s'), 'Group::createdAt should return correct date');
        }
    }

    /**
     * @dataProvider groupDataProvider
     */
    public function testToSkillWithoutCreatedAt(Group $group, array $expected, ?int $delay = null): void
    {
        static::assertEquals($expected['id'], $group->getId(), 'Group::id should return correct id');
        static::assertEquals($expected['name'], $group->getName(), 'Group::name should return correct name');
        static::assertEquals($expected['minimumSize'], $group->getMinimumSize(), 'Group::minimumSize should return correct size');
        static::assertEquals($expected['maximumSize'], $group->getMaximumSize(), 'Group::name maximumSize return correct size');
    }

    /**
     * @dataProvider groupIncorrectDataProvider
     */
    public function testToSkillIncorrectData($input, $expected, ?int $delay = null): void
    {
        static::assertEquals($input, $expected);
    }

    /**
     * @param array $data
     * @return Skill|bool
     */
    private function makeGroup(array $data): Group|bool
    {
        try {
            $group = new Group();
            $group->setId($data['id']);
            $group->setName($data['name']);
            $group->setMinimumSize($data['minimumSize']);
            $group->setMaximumSize($data['maximumSize']);
            if ($data['createdAt']) {
                $group->setCreatedAt();
            }
            if ($data['updatedAt']) {
                $group->setCreatedAt();
            }

            return $group;
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
<?php

namespace App\Tests\Support\Helper;

use App\Entity\Skill;
use App\Entity\TeacherSkill;
use App\Entity\User;
use App\Enum\UserStatus;
use Codeception\Module;
use Codeception\Module\DataFactory;
use DateTimeImmutable;
use League\FactoryMuffin\Faker\Facade;

class Factories extends Module
{
    public function _beforeSuite($settings = []): void
    {
        /** @var DataFactory $factory */
        $factory = $this->getModule('DataFactory');

        $factory->_define(
            User::class,
            [
                'login' => Facade::text(20)(),
                'password' => Facade::text(20)(),
                'age' => Facade::randomNumber(2)(),
                'is_active' => true,
                'phone' => '+0'.Facade::randomNumber(9, true)(),
                'email' => Facade::email()(),
                'preferred' => 'email',
                'created_at' => new DateTimeImmutable(),
                'updated_at' => new DateTimeImmutable(),
                'status' => UserStatus::ACTIVE,
            ]
        );

        $factory->_define(
            Skill::class,
            [
                'name' => Facade::text(20)(),
                'created_at' => new DateTimeImmutable(),
                'updated_at' => new DateTimeImmutable(),
            ]
        );

        $factory->_define(
            TeacherSkill::class,
            [
                // Определите здесь поля модели TeacherSkill
                // Например:
                'teacher' => Facade::randomNumber(1),
                'skill' => Facade::randomNumber(1),
                'created_at' => new DateTimeImmutable(),
                'updated_at' => new DateTimeImmutable(),
            ]
        );
    }
}
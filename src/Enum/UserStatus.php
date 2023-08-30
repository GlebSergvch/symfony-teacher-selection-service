<?php

namespace App\Enum;

enum UserStatus:string
{
    case ACTIVE = 'teacher';
    case DELETED = 'student';

    public static function getLabel(self $value): string {
        return match ($value) {
            UserStatus::ACTIVE => 'active',
            UserStatus::DELETED => 'deleted',
        };
    }
}
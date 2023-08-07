<?php

namespace App\Enum;

enum UserRole:string
{
    case TEACHER = 'teacher';
    case STUDENT = 'student';

    public static function getLabel(self $value): string {
        return match ($value) {
            UserRole::TEACHER => 'teacher',
            UserRole::STUDENT => 'student',
        };
    }
}
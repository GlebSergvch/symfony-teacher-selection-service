<?php

namespace App\Enum;

enum UserStatus:string
{
    case ACTIVE = 'active';
    case DELETED = 'deleted';

    public static function getLabel(self $value): string {
        return match ($value) {
            UserStatus::ACTIVE => 'active',
            UserStatus::DELETED => 'deleted',
        };
    }
}
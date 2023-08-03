<?php

namespace App\Dto;

use App\Enum\UserProfileGender;

class UserProfileDto
{
    public int $user_id;
    public string $firstname;
    public string $middlename;
    public string $lastname;
    public UserProfileGender $gender;
}
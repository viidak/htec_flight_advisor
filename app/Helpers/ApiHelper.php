<?php

namespace App\Helpers;

use App\Models\User;

class ApiHelper
{
    public static function isAdmin($user)
    {
        return $user->type === User::ADMIN_ROLE;
    }
}

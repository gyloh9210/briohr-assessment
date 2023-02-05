<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function getUser(int $userId)
    {
        $users = json_decode(Storage::disk('local')->get('mock-data/users.json'), true);

        return Arr::first($users, function ($user) use ($userId) {
            return $user['id'] === $userId;
        });
    }
}

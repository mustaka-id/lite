<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Profile;

class UserObserver
{
    public function created(User $user)
    {
        $user->profile()->create([
            'nationality' => 'Indonesia'
        ]);
    }
}

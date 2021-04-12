<?php

namespace CustomD\UserSecurityRecovery\Models;

use Illuminate\Database\Eloquent\Model;
use CustomD\UserSecurityRecovery\Models\UserRecovery;

class RecoveryKey extends Model
{

    public function user()
    {
        return $this->hasOneThrough(
            config('user-security-recovery.user_model') ?? config('auth.providers.users.model'),
            UserRecovery::class,
            null,
            null,
            null,
            null
        );
    }
}

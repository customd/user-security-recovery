<?php

namespace CustomD\UserSecurityRecovery\Contracts;

use Illuminate\Foundation\Auth\User as Authenticatable;
use CustomD\UserSecurityRecovery\Exceptions\SecurityException;
use CustomD\UserSecurityRecovery\Interfaces\RecoveryInterface;

trait HasUser
{
    protected ?Authenticatable $user = null;

    public function setUser(?Authenticatable $user): RecoveryInterface
    {
        $this->user = $user;

        return $this;
    }


    protected function validateUser()
    {
        if (empty($this->user)) {
            throw new SecurityException('User is not set');
        }
    }

    protected function getUserId()
    {
        return $this->user->getAttribute(config('user-security-recovery.user_model_primary_key') ?? 'id');
    }
}

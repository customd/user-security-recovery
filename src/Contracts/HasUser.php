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


    protected function validateUser(): static
    {
        if (empty($this->user)) {
            throw new SecurityException('User is not set');
        }
        return $this;
    }

    protected function getUserId(): int
    {
        return $this->user->getAttribute(config('user-security-recovery.user_model_primary_key') ?? 'id');
    }
}

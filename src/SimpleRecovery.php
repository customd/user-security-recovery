<?php

namespace CustomD\UserSecurityRecovery;

use CustomD\UserSecurityRecovery\Contracts\HasRecovery;
use CustomD\UserSecurityRecovery\Contracts\HasUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use CustomD\UserSecurityRecovery\Interfaces\RecoveryInterface;

abstract class SimpleRecovery implements RecoveryInterface
{
    use HasUser;
    use HasRecovery;

    public function __construct(?Authenticatable $user = null)
    {
        $this->type = get_called_class();
        $this->setUser($user);
    }
}

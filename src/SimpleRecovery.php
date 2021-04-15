<?php

namespace CustomD\UserSecurityRecovery;

use CustomD\UserSecurityRecovery\Contracts\hasRecovery;
use CustomD\UserSecurityRecovery\Contracts\HasUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use CustomD\UserSecurityRecovery\Interfaces\RecoveryInterface;

abstract class SimpleRecovery implements RecoveryInterface
{
    use HasUser;
    use hasRecovery;

    /**
     * Constructs our instance.
     *
     * @param \App\Model\User|null $user
     * @param string|null $privateKey
     *
     * @throws SecurityException
     */
    public function __construct(?Authenticatable $user = null)
    {
        $this->type = get_called_class();
        $this->setUser($user);
    }
}

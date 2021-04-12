<?php

namespace CustomD\UserSecurityRecovery;

use CustomD\UserSecurityRecovery\Contracts\HasEncryptedRecovery;
use CustomD\UserSecurityRecovery\Contracts\HasUser;
use CustomD\UserSecurityRecovery\Interfaces\RecoveryInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;

abstract class EncryptedRecovery implements RecoveryInterface
{
    use HasEncryptedRecovery;
    use HasUser;

    public function __construct(?Authenticatable $user = null, ?string $privateKey = null)
    {
        $this->type = get_called_class();
        $this->setUser($user);
        $this->setPrivateKey($privateKey);
    }
}

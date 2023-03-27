<?php

namespace CustomD\UserSecurityRecovery\Actions;

use Throwable;
use Illuminate\Foundation\Auth\User;
use CustomD\UserSecurityRecovery\Models\UserRecovery;

class ValidateSecurityAnswerAction
{
    protected User $user; 

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function execute(int $questionId, string $answer): bool
    {

        $recoveryType = UserRecovery::where('id', $questionId)->select('type')->first();

        /** @var \CustomD\UserSecurityRecovery\EncryptedRecovery $recovery */
        $recovery = resolve($recoveryType->type)->setUser($this->user);

        try {
            $recovery->setRecoveryRecord($questionId)->verifyRecoveryAnswer($answer);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}

<?php

namespace CustomD\UserSecurityRecovery\Models;

use App\Security\Recovery\Base;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use CustomD\UserSecurityRecovery\Models\RecoveryKey;

class UserRecovery extends Model
{
    public function recoveryKey()
    {
        return $this->belongsTo(RecoveryKey::class);
    }

    public function user()
    {
        return $this->belongsTo(
            config('user-security-recovery.user_model') ?? config('auth.providers.users.model'),
            config('user-security-recovery.user_model_primary_key')
        );
    }

    public function setAnswerAttribute($answer): void
    {
        $answer = $this->isAnswerHashed($answer) ? $answer : Hash::make($answer);
        $this->attributes['answer'] = $answer;
    }

    /**
     * Gets our recovery attribute.
     *
     * @return \App\Security\Recovery\Base
     */
    public function getRecoveryAttribute(): Base
    {
        $class = $this->type;

        $securityRecovery = new $class($this->user);
        $securityRecovery->setRecoveryRecord($this);

        return $securityRecovery;
    }



    /**
     * Check if the password is already hashed.
     *
     * @param string $password
     *
     * @return bool
     */
    protected function isAnswerHashed(string $answer): bool
    {
        return \strlen($answer) === 60 && preg_match('/^\$2y\$/', $answer);
    }


    /**
     * Validates the current password.
     *
     * @param string $clearText
     *
     * @return bool
     */
    public function validateAnswer(string $clearText): bool
    {
        return Hash::check($clearText, $this->answer);
    }
}

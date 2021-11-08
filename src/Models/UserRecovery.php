<?php

namespace CustomD\UserSecurityRecovery\Models;

use CustomD\UserSecurityRecovery\Interfaces\RecoveryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use CustomD\UserSecurityRecovery\Models\RecoveryKey;

/**
 * CustomD\UserSecurityRecovery\Models\UserRecovery
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $recovery_key_id
 * @property string $type
 * @property string|null $question
 * @property string $answer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \CustomD\UserSecurityRecovery\Models\RecoveryKey|null $recoveryKey
 * @property-read \Illuminate\Foundation\Auth\User|null $user
 * @property CustomD\UserSecurityRecovery\Interfaces\RecoveryInterface|null $recovery
 * @method static \Illuminate\Database\Eloquent\Builder|UserRecovery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRecovery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRecovery query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRecovery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRecovery whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRecovery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRecovery whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    public function setAnswerAttribute($answer): self
    {
        $answer = $this->isAnswerHashed($answer) ? $answer : Hash::make($answer);
        $this->attributes['answer'] = $answer;
        return $this;
    }

    /**
     * Gets our recovery attribute.
     */
    public function getRecoveryAttribute(): RecoveryInterface
    {
        $class = $this->type;

        $securityRecovery = new $class($this->user);
        $securityRecovery->setRecoveryRecord($this);

        return $securityRecovery;
    }



    /**
     * Check if the password is already hashed.
     */
    protected function isAnswerHashed(string $answer): bool
    {
        return \strlen($answer) === 60 && preg_match('/^\$2y\$/', $answer);
    }


    /**
     * Validates the current password.
     */
    public function validateAnswer(string $clearText): bool
    {
        return Hash::check($clearText, $this->answer);
    }
}

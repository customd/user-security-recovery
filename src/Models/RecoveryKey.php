<?php

namespace CustomD\UserSecurityRecovery\Models;

use Illuminate\Database\Eloquent\Model;
use CustomD\UserSecurityRecovery\Models\UserRecovery;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * CustomD\UserSecurityRecovery\Models\RecoveryKey
 *
 * @property int $id
 * @property string|null $key
 * @property string $iv
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Foundation\Auth\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|RecoveryKey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecoveryKey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RecoveryKey query()
 * @method static \Illuminate\Database\Eloquent\Builder|RecoveryKey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecoveryKey whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecoveryKey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RecoveryKey whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RecoveryKey extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough<\Illuminate\Foundation\Auth\User>
     */
    public function user(): HasOneThrough
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

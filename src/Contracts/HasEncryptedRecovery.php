<?php

namespace CustomD\UserSecurityRecovery\Contracts;

use CustomD\EloquentAsyncKeys\Keypair;
use CustomD\UserSecurityRecovery\Models\RecoveryKey;
use CustomD\UserSecurityRecovery\Models\UserRecovery;
use CustomD\EloquentAsyncKeys\Facades\EloquentAsyncKeys;
use CustomD\UserSecurityRecovery\Exceptions\SecurityException;
use CustomD\UserSecurityRecovery\Exceptions\SecurityNotFoundException;

trait HasEncryptedRecovery
{
    use HasRecovery;

    protected ?string $privateKey = null;


     /**
     * sets the private key value for the current instance.
     *
     * @param string|null $privateKey
     *
     * @return self
     */
    public function setPrivateKey(?string $privateKey): self
    {
        $this->privateKey = $privateKey;

        return $this;
    }


    public function validateHasPrivateKey()
    {
        if (stripos($this->privateKey, '-----BEGIN PRIVATE KEY-----') === false) {
            throw new SecurityException('Please pass a valid Private Key');
        }
    }


    protected function generateKeystore()
    {
        $keystore = EloquentAsyncKeys::setKeys(null, $this->privateKey);
        $keystore->setNewPassword($this->recoveryAnswer, true);

        return $keystore;
    }

    public function getRecoveryKey(): string
    {
        $this->verifyRecoveryAnswer();

        $recoveryKeyData = $this->recoveryRecord->recoveryKey;

        $rsa = EloquentAsyncKeys::setKeys(
            null,
            $recoveryKeyData->key,
            $this->getRecoveryAnswer(),
            hex2bin($recoveryKeyData->iv)
        );

        return $rsa->getDecryptedPrivateKey();
    }


    /**
     * stores the new recovery question.
     *
     * @return self
     */
    public function store(): self
    {
        $this->validateHasPrivateKey();
        $this->validateUser();
        $this->validateQuestionAnswerSet();

        $keystore = $this->generateKeystore();

        $recoveryKey = new RecoveryKey();
        $recoveryKey->iv = $keystore->getSalt();
        $recoveryKey->key = $keystore->getPrivateKey();
        $recoveryKey->save();

        $userRecovery = new UserRecovery();
        $userRecovery->user_id = $this->getUserId();
        $userRecovery->question = $this->getRecoveryQuestion();
        $userRecovery->answer = $this->getRecoveryAnswer();
        $userRecovery->type = $this->type;
        $userRecovery->recovery_key_id = $recoveryKey->id;
        $userRecovery->save();

        return $this;
    }


    public function update(): self
    {

        $this->validateHasPrivateKey();
        $this->validateUser();
        $this->validateQuestionAnswerSet();
        if (! $this->recoveryRecord) {
            throw new SecurityException("Cannot update wihtout first loading the recovery record");
        }

        $this->recoveryRecord->question = $this->getRecoveryQuestion();
        $this->recoveryRecord->answer = $this->getRecoveryAnswer();
        $this->recoveryRecord->save();

        $keystore = $this->generateKeystore();
        $recoveryKey = RecoveryKey::findOrFail($this->recoveryRecord->recovery_key_id);
        $recoveryKey->iv = $keystore->getSalt();
        $recoveryKey->key = $keystore->getPrivateKey();
        $recoveryKey->save();

        return $this;
    }
}

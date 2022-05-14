<?php

namespace CustomD\UserSecurityRecovery\Contracts;

use CustomD\UserSecurityRecovery\Models\UserRecovery;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use CustomD\UserSecurityRecovery\Exceptions\SecurityException;
use CustomD\UserSecurityRecovery\Exceptions\SecurityNotFoundException;

trait HasRecovery
{
    use HasUser;

    protected ?string $recoveryQuestion = null;

    protected ?string  $recoveryAnswer = null;

    protected ?UserRecovery $recoveryRecord = null;

    protected bool $requiresQuestion = true;

    protected string $type;

    public function findRecoveryRecord(): static
    {
        $recoveries = UserRecovery::where('type', $this->type)->where('user_id', $this->getUserId());

        if ($this->recoveryQuestion !== null) {
            $recoveries->where('question', $this->getRecoveryQuestion());
        }
        try {
            $this->recoveryRecord = $recoveries->firstOrFail();
        } catch (ModelNotFoundException $notFound) {
            throw new SecurityNotFoundException('No Recovery record found', $notFound->getCode(), $notFound);
        }

        return $this;
    }


    public function verifyRecoveryAnswer(?string $answer = null, bool $throw = true): bool
    {
        if ($answer) {
            $this->setRecoveryAnswer($answer);
        }

        $this->recoveryRecord ?? $this->findRecoveryRecord();

        if ($this->recoveryRecord === null) {
            throw new SecurityNotFoundException('No Recovery record found');
        }

        $valid = $this->recoveryRecord->validateAnswer($this->getRecoveryAnswer());
        throw_if(($throw && ! $valid), new SecurityNotFoundException('Password not valid'));

        return $valid;
    }

    /**
     * returns the current recovery answer.
     *
     * @return string|null
     */
    public function getRecoveryAnswer(): ?string
    {
        return $this->recoveryAnswer;
    }

    /**
     * returns the current recovery question.
     *
     * @return string|null
     */
    public function getRecoveryQuestion(): ?string
    {
        return $this->recoveryQuestion;
    }

    public function validateQuestionAnswerSet(): static
    {
        if ($this->requiresQuestion && empty($this->getRecoveryQuestion())) {
            throw new SecurityException('Question is not set');
        }

        if (empty($this->getRecoveryAnswer())) {
            throw new SecurityException('Answer is not set');
        }

        return $this;
    }

    /**
     * stores the new recovery question.
     *
     * @return static
     */
    public function store(): static
    {
        $this->validateUser()->validateQuestionAnswerSet();

        $userRecovery = new UserRecovery();
        $userRecovery->user_id = $this->getUserId();
        $userRecovery->question = $this->getRecoveryQuestion();
        $userRecovery->answer = $this->getRecoveryAnswer();
        $userRecovery->type = $this->type;

        $userRecovery->save();

        return $this;
    }

    public function update(): static
    {
        $this->validateUser()->validateQuestionAnswerSet();

        if (! $this->recoveryRecord) {
            throw new SecurityException("Cannot update wihtout first loading the recovery record");
        }

        $this->recoveryRecord->question = $this->getRecoveryQuestion();
        $this->recoveryRecord->answer = $this->getRecoveryAnswer();
        $this->recoveryRecord->save();

        return $this;
    }

    /**
     * Delete the current recovery record.
     *
     * @return static
     */
    public function destroyRecoveryKey(): static
    {
        $this->validateUser();
        $this->findRecoveryRecord();
        $this->recoveryRecord->delete();

        return $this;
    }



    /**
     * sets the answer for the recovery option.
     *
     * @param string $string
     *
     * @return static
     */
    public function setRecoveryAnswer(string $string): static
    {
        $this->recoveryAnswer = preg_replace('/[^a-z\d]/', '', strtolower($string));

        return $this;
    }

    /**
     * sets the current recovery question.
     *
     * @param string $question
     *
     * @return static
     */
    public function setRecoveryQuestion(string $question): static
    {
        $this->recoveryQuestion = $question;

        return $this;
    }

    /**
     * Sets the user recovery record.
     *
     * @param UserRecovery|int $recoveryRecord
     */
    public function setRecoveryRecord(UserRecovery|int $recoveryRecord): static
    {

        if (! $recoveryRecord instanceof UserRecovery) {
            $recoveryRecord = UserRecovery::findOrFail($recoveryRecord);
        }

        if ($recoveryRecord->type !== $this->type) {
            throw new SecurityException("Failed to load as types mismatch");
        }

        $this->recoveryRecord = $recoveryRecord;

        if ($this->requiresQuestion) {
            $this->setRecoveryQuestion($recoveryRecord->question);
        }

        return $this;
    }
}

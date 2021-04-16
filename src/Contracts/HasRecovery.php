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

    public function findRecoveryRecord()
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
    }


    public function verifyRecoveryAnswer(?string $answer = null)
    {

        if ($answer) {
            $this->setRecoveryAnswer($answer);
        }

        $this->recoveryRecord ?? $this->findRecoveryRecord();

        if ($this->recoveryRecord === null) {
            throw new SecurityNotFoundException('No Recovery record found');
        }
        return $this->recoveryRecord->validateAnswer($this->getRecoveryAnswer());
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

    public function validateQuestionAnswerSet(): void
    {
        if ($this->requiresQuestion && empty($this->getRecoveryQuestion())) {
            throw new SecurityException('Question is not set');
        }

        if (empty($this->getRecoveryAnswer())) {
            throw new SecurityException('Answer is not set');
        }
    }

    /**
     * stores the new recovery question.
     *
     * @return self
     */
    public function store(): self
    {
        $this->validateUser();
        $this->validateQuestionAnswerSet();

        $userRecovery = new UserRecovery();
        $userRecovery->user_id = $this->getUserId();
        $userRecovery->question = $this->getRecoveryQuestion();
        $userRecovery->answer = $this->getRecoveryAnswer();
        $userRecovery->type = $this->type;

        $userRecovery->save();

        return $this;
    }

    public function update(): self
    {

        $this->validateUser();
        $this->validateQuestionAnswerSet();
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
     * @return self
     */
    public function destroyRecoveryKey()
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
     * @return self
     */
    public function setRecoveryAnswer(string $string): self
    {
        $this->recoveryAnswer = preg_replace('/[^a-z\d]/', '', strtolower($string));

        return $this;
    }

    /**
     * sets the current recovery question.
     *
     * @param string $question
     *
     * @return self
     */
    public function setRecoveryQuestion(string $question): self
    {
        $this->recoveryQuestion = $question;

        return $this;
    }

    /**
     * Sets the user recovery record.
     *
     * @param UserRecovery $recoveryRecory
     */
    public function setRecoveryRecord($recoveryRecory)
    {

        if (! $recoveryRecory instanceof UserRecovery) {
            $recoveryRecory = UserRecovery::find($recoveryRecory);
        }

        if ($recoveryRecory->type !== $this->type) {
            throw new SecurityException("Failed to load as types mismatch");
        }

        $this->recoveryRecord = $recoveryRecory;

        if ($this->requiresQuestion) {
            $this->setRecoveryQuestion($recoveryRecory->question);
        }
    }
}

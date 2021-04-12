<?php

namespace CustomD\UserSecurityRecovery\Contracts;

use CustomD\UserSecurityRecovery\Models\UserRecovery;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use CustomD\UserSecurityRecovery\Exceptions\SecurityException;
use CustomD\UserSecurityRecovery\Exceptions\SecurityNotFoundException;

trait hasRecovery
{



    protected ?string $recoveryQuestion = null;

    protected string  $recoveryAnswer;

    protected ?UserRecovery $recoveryRecord = null;


    public function findRecoveryRecord()
    {
        $recoveries = UserRecovery::where('type', $this->type)->where('user_id', $this->user->id);

        if ($this->recoveryQuestion !== null) {
            $recoveries->where('question', $this->getRecoveryQuestion());
        }
        try {
            $this->recoveryRecord = $recoveries->firstOrFail();
        } catch (ModelNotFoundException $notFound) {
            throw new SecurityNotFoundException('No Recovery record found', $notFound->getCode(), $notFound);
        }
    }


    public function verifyRecoveryAnswer()
    {
        if ($this->recoveryRecord === null) {
            throw new SecurityNotFoundException('No Recovery record found');
        }
        return $this->recoveryRecord->validateAnswer($this->getRecoveryAnswer);
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
        $userRecovery->user_id = $this->user->id;
        $userRecovery->question = $this->getRecoveryQuestion();
        $userRecovery->answer = $this->getRecoveryAnswer();
        $userRecovery->type = $this->type;

        $userRecovery->save();

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
}

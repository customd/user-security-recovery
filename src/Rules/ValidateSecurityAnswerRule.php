<?php

namespace App\Rules;

use CustomD\UserSecurityRecovery\Actions\ValidateSecurityAnswerAction;
use Illuminate\Foundation\Auth\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\DataAwareRule;

class ValidateSecurityAnswerRule implements Rule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array
     */
    protected $data = []; 
    
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(protected string $questionCol = 'security_question_id', protected ?User $user = null)
    {
    }
    
    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
 
        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (new ValidateSecurityAnswerAction($this->user ?? auth()->user()))->execute($this->data[$this->questionCol], $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The security answer is invalid';
    }
}

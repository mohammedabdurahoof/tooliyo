<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DomainNameValidation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
            return (bool) preg_match('/^([\w\d\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/i', $value);
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Invalid domain name. Please check again!');
    }
}

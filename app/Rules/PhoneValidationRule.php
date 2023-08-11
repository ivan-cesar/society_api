<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneValidationRule implements Rule
{
    public function passes($attribute, $value)
    {
        // Implement your phone number validation logic here.
        // You can use regular expressions or any other method to validate the phone number.

        // Example: Validate that the value is a valid phone number.
        return preg_match('/^\d{10}$/', $value);
    }

    public function message()
    {
        return 'The :attribute must be a valid phone number.';
    }
}

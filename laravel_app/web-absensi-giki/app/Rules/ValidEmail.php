<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Basic email format validation
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail('Format email tidak valid.');
            return;
        }

        // Extract domain from email
        $domain = substr(strrchr($value, "@"), 1);

        // Check if domain has MX records (can receive emails)
        if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
            $fail('Email tidak valid. Domain tidak dapat menerima email.');
            return;
        }
    }
}

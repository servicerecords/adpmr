<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use libphonenumber\PhoneNumberUtil;

class PhoneNumber implements Rule
{
    private $message = null;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($message = null)
    {
        $this->message = $message;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $parsedNumber = $phoneUtil->parse($value);
            $country = $phoneUtil->getRegionCodeForNumber($parsedNumber);

            if (!$country) {
                return false;
            }

            return $phoneUtil->isValidNumber($parsedNumber);

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message ?? 'Enter a valid telephone number';
    }
}

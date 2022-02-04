<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Postcode implements Rule
{
    private $message = null;

    private $country = null;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $message = null, $country = null)
    {
        $this->message = $message;
        $this->country = trim(strtolower($country));
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
        $regex = "/^(([A-Z]{1,2}[0-9][A-Z0-9]?|ASCN|STHL|TDCU|BBND|[BFS]IQQ|PCRN|TKCA) ?[0-9][A-Z]{2}|BFPO ?[0-9]{1,4}|(KY[0-9]|MSR|VG|AI)[ -]?[0-9]{4}|[A-Z]{2} ?[0-9]{2}|GE ?CX|GIR ?0A{2}|SAN ?TA1)$/";

        $triggerCountries= [
            'united kingdom',
            'ascension',
            'british indian ocean territory',
            'british antarctic territory',
            'falkland islands',
            'gibraltar',
            'pitcairn, henderson, ducie and oeno islands',
            'south georgia and south sandwich islands',
            'saint helena',
            'tristan da cunha',
            'turks and caicos islands'
        ];

        return in_array($this->country, $triggerCountries) ? preg_match($regex, trim(strtoupper($value))) : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message ?? 'Enter a valid UK Postcode';
    }
}

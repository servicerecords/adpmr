<?php

namespace App\Http\Requests;


use App\Rules\PhoneNumber;
use App\Rules\Postcode;

class ApplicantDetailsRequest extends DigitalRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'applicant-name' => [
                'required',
            ],
            'applicant-email-address' => [
                'required',
                'email'
            ],
            'applicant-address-line-1' => [
                'required'
            ],
            'applicant-address-town' => [
                'required'
            ],
            'applicant-address-postcode' => [
                'required',
                new PostCode(null, strtoupper(request()->input('applicant-address-country')))
            ],
            'applicant-address-country' => [
                'required'
            ],
            'applicant-telephone' => [
                'required',
                new PhoneNumber
            ],
            'applicant-details-transfer' => [
                'required'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function messages()
    {
        return [
            'applicant-name.required' => 'Enter your full name',
            'applicant-email-address.required' => 'Enter your email address',
            'applicant-email-address.email' => 'Enter an email address in the correct format, like name@example.com',
            'applicant-address-line-1.required' => 'Enter your house name/number and street address',
            'applicant-address-town.required' => 'Enter your town',
            'applicant-address-postcode.required' => 'Enter the postcode of your address',
            'applicant-address-country.required' => 'Enter a country for your address',
            'applicant-telephone.required' => 'Enter your telephone number, including country code',
            'applicant-details-transfer.required' => 'Select whether to use this information for billing',
        ];
    }

}

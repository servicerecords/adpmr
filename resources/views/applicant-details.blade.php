@extends('layouts.app', ['title' => 'Your Details - '])
@section('pageTitle', 'Your details')

@section('content')
    <form method="post" action="{{ route('applicant-details.save') }}" novalidate>
        <x-textfield label="Your title"
                     field="applicant-title"
                     autocomplete="title"
                     :mandatory="false"
                     :spellcheck="false"
                     hint="Include your title such as Mr, Mrs, Dr. Sir, etc"></x-textfield>
        <x-textfield label="Your full name"
                     field="applicant-name"
                     autocomplete="name"
                     :spellcheck="false"></x-textfield>
        <x-textfield label="Email address"
                     field="applicant-email-address"
                     type="email"></x-textfield>
        <x-textfield label="Building number or name and street"
                     extra="line 1 of 2"
                     field="applicant-address-line-1"
                     autocomplete="address-line1"
                     :full-width="true"></x-textfield>
        <x-textfield label="Building and street line 2 of 2"
                     field="applicant-address-line-2"
                     autocomplete="address-line2"
                     :full-width="true"
                     :hideLabel="true"></x-textfield>
        <x-textfield label="Town or city"
                     autocomplete="address-level2"
                     field="applicant-address-town"></x-textfield>
        <x-textfield label="Postcode"
                     autocomplete="postal-code"
                     field="applicant-address-postcode"></x-textfield>
        <x-country label="Country or territory"
                   field="applicant-address-country"></x-country>
        <x-textfield label="Telephone Number"
                     autocomplete="tel"
                     field="applicant-telephone"
                     type="tel"
                     hint="For international numbers include the country code."></x-textfield>
        <x-radio-group label="Use these details for billing?"
                       field="applicant-details-transfer"
                       :options="$options"></x-radio-group>
        <x-submit-form></x-submit-form>
    </form>
@endsection

@extends('layouts.app', ['title' => 'Serviceperson\'s Details - '])
@section('pageTitle', 'Details of the serviceperson')

@section('content')
    <form method="post" action="{{ route('essential-information.save') }}" novalidate>
        <x-textfield label="First name(s)"
                     hint="Include all middle names"
                     field="serviceperson-first-name"
                     autocomplete="given-name"
                     :spellcheck="false"></x-textfield>
        <x-textfield label="Last name"
                     field="serviceperson-last-name"
                     autocomplete="family-name"
                     :spellcheck="false"></x-textfield>
        <x-textfield label="Place of birth"
                     field="serviceperson-place-of-birth"
                     :mandatory="false"></x-textfield>

        @if(in_array('Service record not held', $errors->all()))
            <x-details :label="'Service record not held'">
                No records are held where the serviceperson was born before {{ session('service', \App\Models\ServiceBranch::NAVY) ? '1890' : '1940' }}.
                Contact <a href="https://www.nationalarchives.gov.uk/">The National Archive</a>.
            </x-details>
        @endif

        <x-date-field label="Date of birth"
                      hint="For example, 31 3 1910. A year of birth is required, the day and month are optional."
                      field="serviceperson-date-of-birth-date"></x-date-field>
        <x-submit-form></x-submit-form>
    </form>
@endsection

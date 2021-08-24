@extends('layouts.app', ['title' => 'Did they die in Service - '])
@section('pageTitle', 'Details of the serviceperson')

@section('content')
    <p class="govuk-body">This application process will not tell you if a service record is held.</p>

    <form method="post" action="{{ route('death-in-service.save') }}" novalidate>
        <x-error-summary :errors="$errors"></x-error-summary>
        <x-radio-group label="Did they die in service?"
                       field="serviceperson-died-in-service"
                       :options="$options"></x-radio-group>
        <x-submit-form></x-submit-form>
    </form>
@endsection

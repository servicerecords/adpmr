@extends('layouts.app', ['title' => 'Did they die in Service - '])
@section('pageTitle', 'Details of the RAF serviceperson')

@section('content')
    <p class="govuk-body">This RAF application process will not tell you if a service record is held. (
wait: remote command exited without exit status or exit signaly-military-service-records/apply-for-someone-elses-records">Apply by post for Army, Navy & Home Guard records</a>
FAILED  )</p>

    <form method="post" action="{{ route('death-in-service.save') }}" novalidate>
        <x-error-summary :errors="$errors"></x-error-summary>
        <x-radio-group label="Did they die in service?"
                       field="serviceperson-died-in-service"
                       :options="$options"></x-radio-group>
        <x-submit-form></x-submit-form>
    </form>
@endsection

@extends('layouts.app', ['title' => 'Which Service - '])
@section('pageTitle', 'Details of the serviceperson')

@section('content')
    @php
        $altLink = "https://www.gov.uk/get-copy-military-service-records/apply-for-someone-elses-records#apply-by-post";
    @endphp

    <p class="govuk-body">
        This application process will not tell you if a service record is held.
        (<a class="govuk-link" href="{{ $altLink }}">Apply by post for Army & Home Guard records</a>)
    </p>

    <form method="post" action="{{ route('service.save') }}" novalidate>
        <x-error-summary :errors="$errors"></x-error-summary>
        <x-radio-group label="Which service did they last serve in?"
                       field="service"
                       :questionTag="'h2'"
                       :selected="session('service', null)"
                       :options="$branches"></x-radio-group>
        <x-submit-form :canCancel="false"></x-submit-form>
    </form>
@endsection

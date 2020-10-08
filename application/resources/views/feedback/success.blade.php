@extends('layouts.app')

@section('content')
    <div class="govuk-panel govuk-panel--confirmation">
        <h1 class="govuk-panel__title">
            Feedback submission complete
        </h1>
        <div class="govuk-panel__body">
            Thank you for your feedback on the {{ env('APP_NAME', 'Apply for a deceased\'s military record') }} service
        </div>
    </div>

@endsection

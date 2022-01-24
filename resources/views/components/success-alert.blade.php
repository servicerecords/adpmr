@props([
    'backLink' => false,
])

@if(session()->has('flash'))
<div class="govuk-success-alert">
    <h2 class="govuk-success-alert--heading">{{ session('flash') }}</h2>
    <p class="govuk-body govuk-!-margin-bottom-0">
        Government services may set additional cookies and, if so, will have their own cookie policy and banner.</p>

    @if($backLink)
    <p class="govuk-body govuk-!-margin-bottom-0  govuk-!-margin-top-4">
        <a href="{{ $backLink }}" class="govuk-link">Return to your previous page</a>
    </p>
    @endif
</div>
@endif

@props([
    'label' => 'Label missing'
])
<div class="govuk-notification-banner" role="region" aria-labelledby="govuk-notification-banner-title" data-module="govuk-notification-banner">
    <div class="govuk-notification-banner__header">
        <h2 class="govuk-notification-banner__title" id="govuk-notification-banner-title">
            {{ $label }}
        </h2>
    </div>
    <div class="govuk-notification-banner__content">
        <p class="govuk-notification-banner__heading">
            {{ $slot }}
        </p>
    </div>
</div>
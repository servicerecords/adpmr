<div id="govuk-cookie-banner" class="govuk-cookie-banner" data-nosnippet role="region" aria-label="Cookies on {{ env('APP_NAME') }}" style="display: none;">
    <div class="govuk-cookie-banner__message govuk-width-container">
        <div class="govuk-grid-row">
            <div class="govuk-grid-column-two-thirds">
                <h2 class="govuk-cookie-banner__heading govuk-heading-m">Cookies on {{ env('APP_NAME') }}</h2>
                <div class="govuk-cookie-banner__content">
                    <p>We use some essential cookies to make this service work.</p>
                    <p>We’d also like to use analytics cookies so we can understand how you use the service and make improvements.</p>
                </div>
            </div>
        </div>
        <div class="govuk-button-group">
            <button value="accept" type="button" name="cookies" class="govuk-button" data-module="govuk-button">
                Accept analytics cookies
            </button>
            <button value="reject" type="button" name="cookies" class="govuk-button" data-module="govuk-button">
                Reject analytics cookies
            </button>
            <a class="govuk-link" href="{{ route('cookie-policy') }}">View cookies</a>
        </div>
    </div>

    <div class="govuk-cookie-banner__message govuk-width-container" style="display:none">
        <div class="govuk-grid-row">
            <div class="govuk-grid-column-two-thirds">
                <div class="govuk-cookie-banner__content">
                    <p>You've accepted additional cookies. You can <a class="govuk-link" href="{{ route('cookie-policy') }}">change your cookie settings</a> at any time.</p>
                </div>
            </div>
        </div>
        <div class="govuk-button-group">
            <a href="#" role="button" draggable="false" class="govuk-button" data-module="govuk-button" data-action="hide-cookie-banner">
                Hide this message
            </a>
        </div>
    </div>
</div>

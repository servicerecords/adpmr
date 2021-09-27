{{--<div id="global-cookie-message" class="govuk-clearfix" data-module="cookie-banner" role="region"--}}
{{--     aria-label="cookie banner" data-nosnippet="" style="display: none;">--}}
{{--    <div class="govuk-cookie-message__request govuk-width-container">--}}
{{--        <div class="govuk-grid-row">--}}
{{--            <div class="govuk-grid-column-two-thirds">--}}
{{--                <div>--}}
{{--                    <h2 class="govuk-heading-m">Cookies on {{ env('APP_NAME') }}</h2>--}}
{{--                    <p class="govuk-body">We use <a class="govuk-link" href="{{ route('cookie-policy') }}">cookies to collect--}}
{{--                            information</a> about how you use GOV.UK. We use this information to make the website work--}}
{{--                        as well as possible and improve government services.</p>--}}
{{--                </div>--}}
{{--                <div>--}}
{{--                    <div class="govuk-button-group">--}}
{{--                        <button value="accept" type="button" name="cookies" class="govuk-button" data-module="govuk-button">--}}
{{--                            Accept analytics cookies--}}
{{--                        </button>--}}
{{--                        <button value="reject" type="button" name="cookies" class="govuk-button" data-module="govuk-button">--}}
{{--                            Reject analytics cookies--}}
{{--                        </button>--}}
{{--                        <a class="govuk-link" href="{{ route('cookie-policy') }}">View cookies</a>--}}
{{--                    </div>--}}

{{--                    <div--}}
{{--                        class="govuk-grid-column-full govuk-grid-column-one-half-from-desktop govuk-!-padding-0 govuk-!-padding-right-3">--}}
{{--                        <button class="govuk-button govuk-!-width-full govuk-!-margin-bottom-1" type="submit"--}}
{{--                                data-module="track-click" data-accept-cookies="true" data-track-category="cookieBanner"--}}
{{--                                data-track-action="Cookie banner accepted">Accept analytics cookies--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                    <div--}}
{{--                        class="govuk-grid-column-full govuk-grid-column-one-half-from-desktop govuk-!-padding-0 govuk-!-padding-right-3">--}}
{{--                        <button class="govuk-button govuk-!-width-full govuk-!-margin-bottom-1" type="submit"--}}
{{--                                data-module="track-click" data-accept-cookies="true" data-track-category="cookieBanner"--}}
{{--                                data-track-action="Cookie banner rejected">Reject analytics cookies--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                    <div--}}
{{--                        class="govuk-grid-column-full govuk-grid-column-one-half-from-desktop govuk-!-padding-0 govuk-!-padding-left-3">--}}
{{--                        <a class="govuk-button govuk-!-width-full govuk-!-margin-bottom-1" role="button"--}}
{{--                           data-module="track-click" data-track-category="cookieBanner"--}}
{{--                           data-track-action="Cookie banner settings clicked" href="{{ route('cookie-policy') }}">View cookies</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="govuk-cookie-message__accepted govuk-width-container" tabindex="-1">--}}
{{--        <p class="govuk-body" role="alert">You’ve accepted all cookies.--}}
{{--            You can <a class="govuk-link" href="{{ route('cookie-policy') }}" data-module="track-click"--}}
{{--                       data-track-category="cookieBanner"--}}
{{--                       data-track-action="Cookie banner settings clicked from confirmation">change your cookie--}}
{{--                settings</a> at any time.--}}
{{--        </p>--}}
{{--        <a href="#" class="govuk-hide-button govuk govuk-link" data-hide-cookie-banner="true" data-module="track-click"--}}
{{--           data-track-category="cookieBanner" data-track-action="Hide cookie banner">Hide<span class="govuk-visually-hidden"> cookies message</span></a>--}}
{{--    </div>--}}
{{--</div>--}}


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

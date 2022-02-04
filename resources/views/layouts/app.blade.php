<!DOCTYPE html>
<html lang="en" class="govuk-template">

<head>
    <meta charset="utf-8">
    <title>{{ $errors->any() ? 'Error: ' : '' }}{{ $title ?? '' }}{{ env('APP_NAME', 'The best place to find government services and information') }} - GOV.UK</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0b0c0c">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" sizes="16x16 32x32 48x48" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="mask-icon" href="{{ asset('images/govuk-mask-icon.svg') }}" color="#0b0c0c">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/govuk-apple-touch-icon-180x180.png') }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ asset('images/govuk-apple-touch-icon-167x167.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/govuk-apple-touch-icon-152x152.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/govuk-apple-touch-icon.png') }}">

    <!--[if !IE 8]><!-->
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <!--<![endif]-->

    <!--[if IE 8]>
    <link href="{{ asset('css/all-ie8.css') }}" rel="stylesheet">
    <![endif]-->

    <!--[if lt IE 9]>
    <script src="/html5-shiv/html5shiv.js"></script>
    <![endif]-->

    @stack('styles')

    <meta property="og:image" content="{{ asset('images/govuk-opengraph-image.png') }}">
    <meta name="description" content="Apply for the records of someone who's deceased">
</head>

<body class="govuk-template__body">
<script>
    document.body.className = ((document.body.className) ? document.body.className + ' js-enabled' : 'js-enabled');
</script>

<a href="#main-content" class="govuk-skip-link">Skip to main content</a>

<x-cookie-banner></x-cookie-banner>

@include('partials.header')

<div class="govuk-width-container ">
    <x-phase-banner></x-phase-banner>
    <x-back-button></x-back-button>
    <main class="govuk-main-wrapper" id="main-content" role="main">
        <div class="govuk-grid-row">
            <div class="govuk-grid-column-two-thirds">
                <x-success-alert :backLink="$back_link ?? false"></x-success-alert>

                <x-error-summary :errors="$errors"></x-error-summary>

                <h1 class="govuk-heading-l">
                    @yield('pageTitle')
                </h1>
                @yield('content')
            </div>
        </div>
    </main>
</div>

@include('partials.footer')

<script src="{{ asset('js/govuk.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script>
    window.GOVUKFrontend.initAll()
    window.GOVUKFrontend.CookieBanner.init()
</script>
<script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GA_ID', 'UA-176740731-1') }}"></script>
<script>
    window.dataLayer = window.dataLayer || []
    function gtag() { dataLayer.push(arguments) }
    gtag('js', new Date())
    gtag('config', '{{ env('GA_ID', 'UA-176740731-1') }}')
</script>
@stack('scripts')
<script src="//rum-static.pingdom.net/pa-5f6b3962f07a4c001300036a.js" async></script>
</body>

</html>

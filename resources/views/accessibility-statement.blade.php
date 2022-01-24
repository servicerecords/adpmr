@extends('layouts.app', ['title' => 'Accessibility statement - '])
@section('pageTitle', 'Accessibility statement for ' . env('APP_NAME', 'Apply for a deceased\'s military record'))

@section('content')
    <p class="govuk-body">This website is run by the Ministry of Defence. We want as many people as possible to be able
        to use this website. For example, that means you should be able to:</p>

    <ul class="govuk-list govuk-list--bullet">
        <li>change colours, contrast levels and fonts</li>
        <li>zoom in up to 300% without the text spilling off the screen</li>
        <li>navigate most of the website using just a keyboard</li>
        <li>navigate most of the website using speech recognition software</li>
        <li>listen to most of the website using a screen reader (including the most recent versions of JAWS, NVDA and
            VoiceOver)
        </li>
    </ul>

    <p class="govuk-body">We've also made the website text as simple as possible to understand.</p>
    <p class="govuk-body">
        <a class="govuk-link" href="https://mcmw.abilitynet.org.uk/">AbilityNet</a>
        has advice on making your device easier to use if you have a disability.</p>

    <h2 class="govuk-heading-m">How accessible this website is</h2>
    <p class="govuk-body">Parts of this website are not fully accessible. For example:</p>
    <ul class="govuk-list govuk-list--bullet">
        <li>Some heading elements are not consistent</li>
        <li>Some error messages are not clearly associated with form controls</li>
        <li>Some of the online pages are difficult to navigate using just a keyboard</li>
    </ul>

    <h2 class="govuk-heading-m">Feedback and contact information</h2>
    <p class="govuk-body">If you have accessibility feedback on this website, please use the
        feedback page at the end of the application.</p>
    <p class="govuk-body">We’ll consider your request and get back to you in 7 days.</p>

    <h2 class="govuk-heading-m">Reporting accessibility problems with this website</h2>
    <p class="govuk-body">We’re always looking to improve the accessibility of this website.
        If you find any problems that are not listed on this page or think we’re not meeting
        accessibility requirements, contact:
        <a href="mailto:DBSCIO-ADPMRFeedback@mod.gov.uk">DBSCIO-ADPMRFeedback@mod.gov.uk</a>
    </p>

    <h2 class="govuk-heading-m">Enforcement procedure</h2>
    <p class="govuk-body">The Equality and Human Rights Commission (EHRC) is responsible for enforcing the Public Sector
        Bodies (Websites and Mobile Applications) (No. 2) Accessibility Regulations 2018 (the 'accessibility regulations').
        If you’re not happy with how we respond to your complaint,
        <a href="https://www.equalityadvisoryservice.com/">contact the Equality Advisory and Support Service (EASS)</a>.</p>

    <h2 class="govuk-heading-m">Technical information about this website’s accessibility</h2>
    <p class="govuk-body">The Ministry of Defence is committed to making its website accessible, in accordance with the
        Public Sector Bodies (Websites and Mobile Applications) (No. 2) Accessibility Regulations 2018.</p>

    <h2 class="govuk-heading-m">Compliance status</h2>
    <p class="govuk-body">This website is partially compliant with the
        <a href="https://www.w3.org/TR/WCAG21/">Web Content Accessibility Guidelines version 2.1</a> AA standard,
        due to the non-compliances, listed below.</p>

    <h2 class="govuk-heading-m">Non-accessible content</h2>
    <p class="govuk-body">The content listed below is non-accessible for the following reasons.</p>
    <ul class="govuk-list govuk-list--number">
        <li>Some pages have illogical headings. This may make it difficult for users to identify which heading introduces the main content. This fails WCAG 2.1 success criterion 1.3.1 (Info and Relationships). We plan to fix this by removing multiple level 1 headings by February 2022. When we publish new content we’ll make sure our use of headings meets accessibility standards.</li>
        <li>Some pages have no instructions to users that a form component is optional. This may be disorientating for users. This fails WCAG 2.1 success criterion 3.3.2 (Labels and Instructions). We plan to fix this by using labels and hint text to identify all optional components by February 2022. When we publish new content we’ll make sure that optional form components meet accessibility requirements.</li>
        <li>Some page elements are not accessible to standard keyboard inputs. This may make it difficult for users to locate the error. This fails WCAG 2.1 success criterion 2.1.1 (Keyboard). We plan to fix this by repositioning the user focus to the correct component by February 2022. When we publish the new content we’ll make sure that all page elements meet accessibility requirements.</li>
        <li>HTML autocomplete is not implemented or implemented incorrectly. This may make it difficult for a cognitive user to complete the form. This fails WCAG 2.1 success criterion 4.1.1 (Parsing). We plan to fix this by using the correct autocomplete attribute value by February 2022. When we publish the new content we’ll make sure that HTML autocomplete meets accessibility requirements.</li>
    </ul>

    <h2 class="govuk-heading-m">Preparation of this accessibility statement</h2>
    <p class="govuk-body">This statement was prepared on 16th December 2020. It was last reviewed on 22th January 2022.</p>
    <p class="govuk-body">This website was last tested on 4 January 2022. The test was carried out by Digital Accessibility Centre (DAC) Ltd.</p>
    <p class="govuk-body">We used this approach to deciding on a sample of pages to test. The test combines technical auditing with disabled user feedback. The test does not list each specific area that requires change but highlights patterns of problems where they exist. The test covered the end-to-end digital service.</p>

@endsection

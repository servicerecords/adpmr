window.GOVUK.analyticsInit = function () {
    "use strict";

    var consentCookie = window.GOVUK.getConsentCookie();

    var dummyAnalytics = {
        addLinkedTrackerDomain: function () {
        },
        setDimension: function () {
        },
        setOptionsForNextPageView: function () {
        },
        trackEvent: function () {
        },
        trackPageview: function () {
        },
        trackShare: function () {
        },
    };

    // Disable analytics by default
    // This will be reversed below, if the consent cookie says usage cookies are allowed
    window['ga-disable-UA-26179049-1'] = true;

    if (consentCookie && consentCookie["usage"]) {
        window['ga-disable-UA-26179049-1'] = false;

        // Load Google Analytics libraries
        GOVUK.StaticAnalytics.load();

        // Use document.domain in dev, preview and staging so that tracking works
        // Otherwise explicitly set the domain as www.gov.uk (and not gov.uk).
        var cookieDomain = (document.domain == 'www.gov.uk') ? '.www.gov.uk' : document.domain;

        var universalId = '<%= Rails.application.config.ga_universal_id %>';
        var secondaryId = '<%= Rails.application.config.ga_secondary_id %>';

        // Configure profiles, setup custom vars, track initial pageview
        var analytics = new GOVUK.StaticAnalytics({
            universalId: universalId,
            cookieDomain: cookieDomain,
            allowLinker: true
        });

        // Make interface public for virtual pageviews and events
        GOVUK.analytics = analytics;

        var linkedDomains = [];
        GOVUK.analytics.addLinkedTrackerDomain(secondaryId, 'govuk', linkedDomains);
    } else {
        GOVUK.analytics = dummyAnalytics
    }
}

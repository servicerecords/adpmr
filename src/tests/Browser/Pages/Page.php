<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Page as BasePage;

abstract class Page extends BasePage
{
    /**
     * Get the global element shortcuts for the site.
     *
     * @return array
     */
    public static function siteElements()
    {
        return [
            '@error-block'        => '.govuk-error-summary',
            '@back-button'        => '.govuk-back-link',
            '@cookie-banner'      => '#govuk-cookie-banner',
            '@cookie-accept'      => '#govuk-cookie-banner button[value="accept"]',
            '@cookie-reject'      => '#govuk-cookie-banner button[value="reject"]',
            '@view-cookies-link'  => '#govuk-cookie-banner .govuk-link',
            '@hide-cookie-banner' => '#govuk-cookie-banner a[data-action="hide-cookie-banner"]',
            '@save-and-continue'  => 'form button.govuk-button',
            '@cancel-application' => 'form .form-group a[data-module="govuk-button"]',
        ];
    }
}

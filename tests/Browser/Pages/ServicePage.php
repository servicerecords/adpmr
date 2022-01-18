<?php

namespace Tests\Browser\Pages;

use App\Models\ServiceBranch;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class ServicePage extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url())
            ->assertInputPresent('service');
//        ->assertRadioNotSelected('service', 'RAF');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements(): array
    {
        return [
            '@raf'        => '[name="service"][value="' . ServiceBranch::RAF . '"]',
            '@navy'       => '[name="service"][value="' . ServiceBranch::NAVY . '"]',
            '@home-guard' => '[name="service"][value="' . ServiceBranch::HOME_GUARD . '"]',
            '@army'       => '[name="service"][value="' . ServiceBranch::ARMY . '"]'
        ];
    }
}

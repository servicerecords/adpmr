<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class DateOfBirth extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '#serviceperson-date-of-birth-date';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param Browser $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements(): array
    {
        return [
            '@year' => '#serviceperson-date-of-birth-date-year',
            '@month' => '#serviceperson-date-of-birth-date-month',
            '@day' => '#serviceperson-date-of-birth-date-day',
        ];
    }

    /**
     * Select the given date.
     *
     * @param \Laravel\Dusk\Browser $browser
     * @param int|null $year
     * @param int|null $month
     * @param int|null $day
     * @return void
     */
    public function selectDate(Browser $browser, int $year = null, int $month = null, int $day = null)
    {
        $browser->value('@year', $year);
        $browser->value('@month', $month);
        $browser->value('@day', $day);
    }
}

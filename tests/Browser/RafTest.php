<?php

namespace Tests\Browser;

use App\Models\Constant;
use App\Models\ServiceBranch;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\DeathInServicePage;
use Tests\Browser\Pages\ServicePage;
use Tests\DuskTestCase;

class RafTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function testRafServiceSelection()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new ServicePage);

            $browser->assertRadioNotSelected('@raf', 'RAF')
                ->press(Constant::SAVE_BUTTON_LABEL)
                ->assertPresent('@error-block')
                ->assertSee('Select a service');

            $browser->radio('@raf', ServiceBranch::RAF)
                ->assertRadioSelected('@raf', ServiceBranch::RAF)
                ->press(Constant::SAVE_BUTTON_LABEL)
                ->assertPathIs('/service/death-in-service');
        });
    }

    /**
     * @throws \Throwable
     */
    public function testRafDiedInService()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new ServicePage);

            $browser->radio('@raf', ServiceBranch::RAF)
                ->assertRadioSelected('@raf', ServiceBranch::RAF)
                ->press(Constant::SAVE_BUTTON_LABEL)
                ->on(new DeathInServicePage)
                ->assertPathIs('/service/death-in-service');

            $browser->assertRadioNotSelected('@died-in-service', Constant::YES)
                ->assertRadioNotSelected('@died-in-service', Constant::NO)
                ->press(Constant::SAVE_BUTTON_LABEL)
                ->assertPresent('@error-block')
                ->assertSee('State if they died in service');

            $browser->radio('@died-in-service', Constant::YES)
                ->press(Constant::SAVE_BUTTON_LABEL);
        });
    }
}

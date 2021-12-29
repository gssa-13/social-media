<?php

namespace Tests\Browser;

use App\Models\Status;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanSeeAllStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function users_can_see_statuses_on_home()
    {
        $statuses = Status::factory()->count(5)->create();

        $this->browse(function (Browser $browser) use($statuses) {
            $browser->visit('/')
                ->waitForText($statuses->first()->body);

            foreach ( $statuses as $status) {
                $browser->assertSee($status->body);
            }
        });
    }
}

<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use App\Models\Status;

class UsersCanSeeAllStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function users_can_see_all_statuses_on_the_homepage()
    {
        $statuses = Status::factory()->count(5)
            ->create([ 'created_at' => now()->subMinute() ]);

        $this->browse(function (Browser $browser) use ($statuses) {
            $browser->visit('/')
                ->waitForText($statuses->first()->body);

            foreach ($statuses as $status) {
                $browser->assertSee($status->body)
                    ->assertSee($status->user->name, 7)
                    ->assertSee($status->created_at->diffForHumans())
                ;
            }
        });

    }
}

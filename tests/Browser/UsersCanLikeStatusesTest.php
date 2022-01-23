<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use App\Models\Status;
use App\Models\User;

class UsersCanLikeStatusesTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @throws \Throwable
     */
    public function users_can_like_and_unlike_statuses()
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $status) {
            $browser->loginAs($user)->visit('/')
                ->waitForText($status->body, 7)
                ->assertSeeIn('@likes-count',0)
                ->press('@like-btn')
                ->waitForText('You Like', 7)
                ->assertSee('You Like')
                ->assertSeeIn('@likes-count',1)

                ->press('@like-btn')
                ->waitForText('Like', 7)
                ->assertSee('Like')
                ->assertSeeIn('@likes-count',0)
            ;
        });
    }


    /**
     * @test
     * @throws \Throwable
     */
    public function guests_users_can_not_like_statuses()
    {
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser) use ($status) {
            $browser->visit('/')
                ->waitForText($status->body, 7)
                ->press('@like-btn')
                ->assertPathIs('/login')
            ;
        });
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function a_status_show_how_many_likes_it_has ()
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser) use($user, $status) {
            $browser->loginAs($user)
                ->visit('/')
                ->waitForText($status->body)
                ->assertSeeIn('@likes-count', 0)
                ->press('@like-btn')
                ->waitForText('You Like')
                ->assertSee('You Like')
                ->assertSeeIn('@likes-count', 1)

                ->press('@like-btn')
                ->waitForText('Like')
                ->assertSee('Like')
                ->assertSeeIn('@likes-count', 0)
            ;
        });
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function users_can_see_likes_and_unlikes_in_real_time()
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $this->browse(function (Browser $browser1, Browser $browser2) use ($user, $status) {
            $browser1->visit('/');

            $browser2->loginAs($user)->visit('/')
                ->waitForText($status->body)
                ->assertSeeIn('@likes-count', 0)
                ->press('@like-btn')
                ->waitForText('You Like', 7)
            ;

            $browser1->assertSeeIn('@likes-count',1);
        });
    }

}

<?php

namespace Tests\Browser;

use App\Models\Status;
use App\Models\User;
use Database\Factories\DatabaseNotificationFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanGetTheirNotificationsTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * @test
     * @throws \Throwable
     */
    public function users_can_see_their_notifications_in_the_navbar()
    {
        $user = User::factory()->create();
        $status = Status::factory()->create();

        $notification = DatabaseNotificationFactory::new()->create([
            'notifiable_id' => $user->id,
            'data' => [
                'link' => route('statuses.show', $status),
                'message' => 'You have received a like'
            ]
        ]);

        $this->browse(function (Browser $browser) use($user, $notification, $status) {
            $browser->loginAs($user)->visit('/')
                ->click('@notifications')
                ->assertSee('You have received a like')
                ->click("@{$notification->id}")
                ->assertUrlIs($status->path())

                ->click('@notifications')
                ->press("@mark-as-read-{$notification->id}")
                ->waitFor("@mark-as-unread-{$notification->id}", 20)
                ->assertMissing("@mark-as-read-{$notification->id}")

                ->press("@mark-as-unread-{$notification->id}")
                ->waitFor("@mark-as-read-{$notification->id}", 20)
                ->assertMissing("@mark-as-unread-{$notification->id}")
            ;
        });
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function users_can_see_their_notifications_in_real_time()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $status = Status::factory()->create(['user_id' => $user1->id]);

        $this->browse(function (Browser $browser1, Browser $browser2) use ($user1, $user2, $status) {
            $browser1->loginAs($user1)->visit('/');

            $browser2->loginAs($user2)->visit('/')->press('@like-btn')
                ->pause(1000);

            $browser1->assertSeeIn('@notifications-count', 1);
        });
    }
}

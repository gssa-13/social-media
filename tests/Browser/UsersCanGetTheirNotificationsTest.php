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
            ;
        });
    }
}

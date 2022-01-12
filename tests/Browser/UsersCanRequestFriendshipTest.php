<?php

namespace Tests\Browser;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersCanRequestFriendshipTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function senders_can_create_and_delete_friendship_requests()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                ->visit( route('users.show', $recipient) )
                ->press('@request-friendship')
                ->waitForText('Cancel request')
                ->assertSee('Cancel request')
                ->visit( route('users.show', $recipient) )
                ->assertSee('Cancel request')
                ->press('@request-friendship')
                ->waitForText('Send friend request')
                ->assertSee('Send friend request')
            ;
        });
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function senders_can_delete_accepted_friendship_requests()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'accepted'
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                ->visit( route('users.show', $recipient) )
                ->assertSee('Remove from my friends')
                ->press('@request-friendship')
                ->waitForText('Send friend request')
                ->assertSee('Send friend request')
                ->visit( route('users.show', $recipient) )
                ->assertSee('Send friend request')
            ;
        });
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function senders_cannot_delete_denied_friendship_requests()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'denied'
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                ->visit( route('users.show', $recipient) )
                ->assertSee('Request denied')
                ->press('@request-friendship')
                ->waitForText('Request denied')
                ->assertSee('Request denied')
                ->visit( route('users.show', $recipient) )
                ->assertSee('Request denied')
            ;
        });
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function recipients_can_accept_friendship_requests()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($recipient)
                ->visit( route('accept-friendships.index') )
                ->assertSee($sender->name)
                ->press('@accept-friendship')
                ->waitForText('is your friend')
                ->assertSee('is your friend')
                ->visit( route('accept-friendships.index') )
                ->assertSee('is your friend')
            ;
        });
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function recipients_can_deny_friendship_requests()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($recipient)
                ->visit( route('accept-friendships.index') )
                ->assertSee($sender->name)
                ->press('@deny-friendship')
                ->waitForText('request denied')
                ->assertSee('request denied')
                ->visit( route('accept-friendships.index') )
                ->assertSee('request denied')
            ;
        });
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function recipients_can_delete_friendship_requests()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($recipient)
                ->visit( route('accept-friendships.index') )
                ->assertSee($sender->name)
                ->press('@delete-friendship')
                ->waitForText('Request deleted')
                ->assertSee('Request deleted')
                ->visit( route('accept-friendships.index') )
                ->assertDontSee('Request deleted')
                ->assertDontSee($sender->name)
            ;
        });
    }
}

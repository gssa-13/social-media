<?php

namespace Tests\Feature;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CanRequestFriendshipTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function authenticated_user_can_send_friendship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $response = $this->actingAs($sender)->postJson( route('friendships.store', $recipient) );

        $response->assertJson([
            'friendship_status' => 'pending'
        ]);

        $this->assertDatabaseHas('friendships',array(
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'pending'
        ));

        $this->actingAs($sender)->postJson( route('friendships.store', $recipient) );

        $this->assertCount(1, Friendship::all());
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function authenticated_user_can_accept_friendship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($recipient)
            ->postJson( route('accept-friendships.store', $sender) );

        $response->assertJson([
            'friendship_status' => 'accepted'
        ]);

        $this->assertDatabaseHas('friendships',array(
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'accepted'
        ));
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function senders_can_delete_sent_friendship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);

        $response = $this->actingAs($sender)
            ->deleteJson( route('friendships.destroy', $sender) );

        $response->assertJson([
            'friendship_status' => 'deleted'
        ]);

        $this->assertDatabaseMissing('friendships',array(
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ));
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function senders_cannot_delete_denied_friendship_request()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'denied'
        ]);

        $response = $this->actingAs($sender)
            ->deleteJson( route('friendships.destroy', $recipient) );

        $response->assertJson([
            'friendship_status' => 'denied'
        ]);

        $this->assertDatabaseHas('friendships',array(
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'denied'
        ));
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function recipients_can_delete_received_friendship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);

        $response = $this->actingAs($recipient)
            ->deleteJson( route('friendships.destroy', $sender) );

        $response->assertJson([
            'friendship_status' => 'deleted'
        ]);

        $this->assertDatabaseMissing('friendships',array(
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ));
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function user_can_deny_a_friendship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($recipient)
            ->deleteJson( route('accept-friendships.destroy', $sender) );

        $response->assertJson([
            'friendship_status' => 'denied'
        ]);

        $this->assertDatabaseHas('friendships',array(
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'denied'
        ));
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function guest_users_cannot_create_friendship_request()
    {
        $recipient = User::factory()->create();

        $response = $this->postJson( route('friendships.store', $recipient) );

        $response->assertStatus(401);
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function guest_users_cannot_delete_friendship_request()
    {
        $recipient = User::factory()->create();

        $response = $this->deleteJson( route('friendships.destroy', $recipient) );

        $response->assertStatus(401);
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function guest_users_cannot_accept_friendship_request()
    {
        $user = User::factory()->create();

        $response = $this->postJson( route('accept-friendships.store', $user) );

        $response->assertStatus(401);
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function guest_users_cannot_deny_friendship_request()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson( route('accept-friendships.destroy', $user) );

        $response->assertStatus(401);
    }
}

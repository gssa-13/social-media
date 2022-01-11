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
    public function can_send_friendship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $this->actingAs($sender)->postJson( route('friendships.store', $recipient) );

        $this->assertDatabaseHas('friendships',array(
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 2
        ));
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function user_can_accept_friendship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 2 //pending
        ]);

        $this->actingAs($recipient)->postJson( route('accept-friendships.store', $sender) );

        $this->assertDatabaseHas('friendships',array(
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 1 // accepted
        ));
    }


    /**
     * @test
     *
     * @throws \Throwable
     */
    public function can_delete_friendship_request()
    {
        $this->withoutExceptionHandling();

        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
        ]);

        $this->actingAs($sender)->deleteJson( route('friendships.destroy', $recipient) );

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
            'status' => 2
        ]);

        $this->actingAs($recipient)->deleteJson( route('accept-friendships.destroy', $sender) );

        $this->assertDatabaseHas('friendships',array(
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 0
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

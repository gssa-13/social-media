<?php

namespace Tests\Feature;

use App\Models\Friendship;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;

class CanGetFriendshipTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_get_friendship()
    {
        $this->withoutExceptionHandling();
//        friendship-status="{{ $friendshipStatus }}"
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $friendship = Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id
        ]);

        $response = $this->actingAs($sender)->getJson(route('friendships.show', $recipient));

        $response->assertJsonFragment([
            'friendship_status' => $friendship->fresh()->status
        ]);
    }

    /** @test */
    function guest_users_cannot_get_friendships()
    {
        $this->getJson(route('friendships.show', 'John Doe'))
            ->assertStatus(401);
    }
}

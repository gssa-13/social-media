<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Friendship;

class CanSeeFriendsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_see_a_list_of_friends()
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        Friendship::factory()->create([
            'recipient_id' => $recipient->id,
            'sender_id' => $sender->id,
            'status' => 'accepted'
        ]);

        $this->actingAs($sender)->get(route('friends.index'))
            ->assertSee($recipient->name);
        $this->actingAs($recipient)->get(route('friends.index'))
            ->assertSee($sender->name);
    }

    /** @test */
    function guests_cannot_access_to_the_list_of_friends()
    {
        $this->get(route('friends.index'))
        ->assertRedirect('login');
    }
}

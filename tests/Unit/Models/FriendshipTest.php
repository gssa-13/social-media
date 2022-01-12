<?php

namespace Tests\Unit\Models;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FriendshipTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_friendship_request_belongs_to_a_sender()
    {
        $sender = User::factory()->create();
        $friendship = Friendship::factory()->create([ 'sender_id' => $sender->id ]);

        $this->assertInstanceOf(User::class, $friendship->sender);
    }

    /** @test */
    public function a_friendship_request_belongs_to_a_recipient()
    {
        $recipient = User::factory()->create();
        $friendship = Friendship::factory()->create([ 'recipient_id' => $recipient->id ]);

        $this->assertInstanceOf(User::class, $friendship->recipient);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

use App\Models\Comment;
use App\Models\Status;
use App\Models\User;

class CanLikeCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_users_can_not_like_comment()
    {
        $comment = Comment::factory()->create();
        $response = $this->postJson(route('comments.like.store', $comment));
        $response->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_user_can_like_comments()
    {
        Notification::fake();

        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->assertCount(0, $comment->likes);

        $response = $this->actingAs($user)->postJson( route('comments.like.store', $comment) );

        $response->assertJsonFragment([ 'likes_count' => 1 ]);

        $this->assertCount(1, $comment->fresh()->likes);

        $this->assertDatabaseHas('likes', ['user_id' => $user->id ]);
    }

    /** @test */
    public function an_authenticated_user_can_unlike_comments()
    {
        Notification::fake();

        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->assertCount(0, $comment->likes );

        $response = $this->actingAs($user)->postJson( route('comments.like.store', $comment) );

        $response->assertJsonFragment([ 'likes_count' => 1 ]);

        $this->assertCount(1, $comment->fresh()->likes);

        $this->assertDatabaseHas('likes', [ 'user_id' => $user->id ]);

        $response2 = $this->actingAs($user)->deleteJson( route('comments.like.destroy', $comment) );

        $response2->assertJsonFragment([ 'likes_count' => 0 ]);

        $this->assertCount(0, $comment->fresh()->likes);


        $this->assertDatabaseMissing('likes', [ 'user_id' => $user->id ]);
    }
}

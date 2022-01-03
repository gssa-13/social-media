<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->assertCount(0, $comment->likes);

        $this->actingAs($user)->postJson( route('comments.like.store', $comment) );

        $this->assertCount(1, $comment->fresh()->likes);

        $this->assertDatabaseHas('likes', ['user_id' => $user->id ]);
    }

    /** @test */
    public function an_authenticated_user_can_unlike_comments()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->assertCount(0, $comment->likes );

        $this->actingAs($user)->postJson( route('comments.like.store', $comment) );

        $this->assertCount(1, $comment->fresh()->likes);

        $this->assertDatabaseHas('likes', [ 'user_id' => $user->id ]);

        $this->actingAs($user)->deleteJson( route('comments.like.destroy', $comment) );

        $this->assertCount(0, $comment->fresh()->likes);

        $this->assertDatabaseMissing('likes', [ 'user_id' => $user->id ]);
    }
}

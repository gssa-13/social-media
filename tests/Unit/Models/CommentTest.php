<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use App\Traits\HasLikes;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

   /** @test */
    public function a_comment_belongs_to_user()
    {
        $comment = Comment::factory()->create();
        $this->assertInstanceOf(User::class, $comment->user);
    }

    /** @test */
    function a_comment_model_must_use_the_trait_has_likes()
    {
        $this->assertClassUsesTrait(HasLikes::class, Comment::class);
    }

    /** @test  */
    public function a_comment_must_have_a_path()
    {
        $comment = Comment::factory()->create();
        // url: statuses/1#comment-id
        $this->assertEquals(
            route('statuses.show', $comment->status_id).'#comment-' . $comment->id,
            $comment->path()
        );

    }
}

<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\User;
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
}

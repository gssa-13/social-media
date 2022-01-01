<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentResourceTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_comment_resources_must_have_the_necessary_fields()
    {
        $comment = Status::factory()->create();

        $commentResource = CommentResource::make($comment)->resolve();
        $this->assertEquals($comment->body, $commentResource['body']);

    }
}

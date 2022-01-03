<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Resources\StatusResource;

class StatusResourceTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_status_resources_must_have_the_necessary_fields()
    {
        $status = Status::factory()->create();
        $comments = Comment::factory()->create([ 'status_id' => $status->id ]);

        $statusResource = StatusResource::make($status)->resolve();

        $this->assertEquals($status->id, $statusResource['id']);
        $this->assertEquals($status->body, $statusResource['body']);
        $this->assertEquals($status->user->name, $statusResource['user_name']);
        $this->assertEquals(
            'https://images.pexels.com/photos/3118694/pexels-photo-3118694.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
            $statusResource['user_avatar']
        );
        $this->assertEquals($status->created_at->diffForHumans(), $statusResource['ago']);
        $this->assertEquals(false, $statusResource['is_liked']);
        $this->assertEquals(0, $statusResource['likes_count']);
        $this->assertEquals( CommentResource::class , $statusResource['comments']->collects);
        $this->assertInstanceOf(Comment::class, $statusResource['comments']->first()->resource );

    }
}

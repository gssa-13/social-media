<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_status_belongs_to_user()
    {
        $status = Status::factory()->create();

        $this->assertInstanceOf(User::class, $status->user);
    }

    /** @test */
    public function a_status_has_many_likes()
    {
        $status = Status::factory()->create();
        Like::factory()->create([ 'status_id' => $status->id ]);

        $this->assertInstanceOf(Like::class, $status->likes->first());
    }

    /** @test */
    public function a_status_can_be_liked()
    {
        $status = Status::factory()->create();

        $this->actingAs( User::factory()->create() );

        $status->like();

        $this->assertEquals(1, $status->likes->count());
    }

    /** @test */
    public function a_status_can_be_liked_once()
    {
        $status = Status::factory()->create();

        $this->actingAs( User::factory()->create() );

        $status->like();

        $this->assertEquals(1, $status->likes->count());

        $status->like();

        $this->assertEquals(1, $status->fresh()->likes->count());
    }

    /** @test */
    public function a_status_knows_if_it_has_been_liked()
    {
        $status = Status::factory()->create();

        $this->assertFalse( $status->isLiked() );

        $this->actingAs( User::factory()->create() );

        $this->assertFalse( $status->isLiked() );

        $status->like();

        $this->assertTrue( $status->isLiked() );
    }

    /** @test */
    public function a_status_can_be_unliked()
    {
        $status = Status::factory()->create();
        $this->actingAs( User::factory()->create() );
        $status->like();
        $this->assertEquals(1, $status->fresh()->likes->count());

        $status->unlike();
        $this->assertEquals(0, $status->fresh()->likes->count());
    }

    /** @test */
    public function a_status_knows_how_many_likes_it_has()
    {
        $status = Status::factory()->create();
        $this->assertEquals(0, $status->likesCount());
        $like = Like::factory()->count(2)->create([ 'status_id' => $status->id ]);
        $this->assertEquals(2, $status->likesCount());
    }

    /** @test */
    public function a_status_has_many_comments()
    {
        $status = Status::factory()->create();
        Comment::factory()->create([ 'status_id' => $status->id ]);
        $this->assertInstanceOf(Comment::class, $status->comments->first());
    }

}

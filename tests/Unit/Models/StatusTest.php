<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Status;
use App\Models\User;
use App\Traits\HasLikes;
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
    public function a_status_has_many_comments()
    {
        $status = Status::factory()->create();
        Comment::factory()->create([ 'status_id' => $status->id ]);
        $this->assertInstanceOf(Comment::class, $status->comments->first());
    }

    /** @test */
    function a_status_model_must_use_the_trait_has_likes()
    {
        $this->assertClassUsesTrait(HasLikes::class, Status::class);
    }

}

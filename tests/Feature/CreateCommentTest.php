<?php

namespace Tests\Feature;

use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateCommentTest extends TestCase
{
    use refreshDataBase;

    /** @test */
    public function authenticated_users_can_comment_statuses()
    {
        $status = Status::factory()->create();
        $user = User::factory()->create();
        $comment = [ 'body' => 'My first comment' ];

        $response = $this->actingAs($user)
            ->postJson( route('statuses.comment.store', $status), $comment );

        $response->assertJson([
            'data' => [ 'body' => 'My first comment' ]
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'status_id' => $status->id,
            'body' => 'My first comment'
        ]);
    }

    /** @test */
    public function guest_users_can_not_comment_statuses()
    {
        $status = Status::factory()->create();
        $comment = [ 'body' => 'My first comment' ];

        $response = $this->postJson( route('statuses.comment.store', $status), $comment );

        $response->assertStatus(401);
    }

    /** @test */
    public function a_comment_require_a_body()
    {
        $status = Status::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson(route('statuses.comment.store', $status), ['body' => '']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors' => ['body']
        ]);
    }
}

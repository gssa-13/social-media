<?php

namespace Tests\Feature;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

use App\Models\User;
use App\Models\Status;
use App\Models\Comment;
use App\Events\CommentCreated;
use App\Http\Resources\CommentResource;


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
    public function an_event_is_fired_when_a_comment_is_created()
    {
        Event::fake([CommentCreated::class]);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        $status = Status::factory()->create();
        $user = User::factory()->create();
        $comment = [ 'body' => 'My first comment' ];

        $this->actingAs($user)
            ->postJson( route('statuses.comment.store', $status), $comment );

        Event::assertDispatched(CommentCreated::class, function ($commentStatusEvent) {
            $this->assertInstanceOf(CommentResource::class, $commentStatusEvent->comment);
            $this->assertTrue(Comment::first()->is($commentStatusEvent->comment->resource));
            $this->assertEventChannelType('public', $commentStatusEvent);
            $this->assertEventChannelName(
                "statuses.{$commentStatusEvent->comment->status_id}.comments",
                $commentStatusEvent
            );
            $this->assertDontBroadcastToCurrentUser($commentStatusEvent);
            return true;
        });
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

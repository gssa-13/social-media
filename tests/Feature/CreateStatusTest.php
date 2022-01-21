<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Broadcast;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Status;
use App\Events\StatusCreated;
use App\Http\Resources\StatusResource;

class CreateStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_create_statuses()
    {

        // 1 given => Getting an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // 2 When => Make a post request to status
        $response = $this->postJson(route('statuses.store'), ['body' => 'My first status']);


        $response->assertJson([
            'data' => ['body' => 'My first status'],
        ]);

        // 3 Then => See a new status from database
        $this->assertDatabaseHas('statuses', [
            'user_id' => $user->id,
            'body' => 'My first status'
        ]);
    }

    /** @test */
    public function an_event_is_fired_when_a_status_is_created()
    {
        Event::fake([StatusCreated::class]);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        // 1 given => Getting an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // 2 When => Make a post request to status
        $this->postJson(route('statuses.store'), ['body' => 'My first status']);

        Event::assertDispatched(StatusCreated::class, function ($statusCreatedEvent) {
            $this->assertInstanceOf(StatusResource::class, $statusCreatedEvent->status);
            $this->assertTrue(Status::first()->is($statusCreatedEvent->status->resource));
            $this->assertEventChannelType('public', $statusCreatedEvent);
            $this->assertEventChannelName('statuses', $statusCreatedEvent);
            $this->assertDontBroadcastToCurrentUser($statusCreatedEvent);
            return true;
        });
    }

    /** @test */
    public function guests_can_not_create_statuses()
    {
        $response = $this->postJson(route('statuses.store'), ['body' => 'My first post']);
        $response->assertStatus(401);
    }

    /** @test */
    public function a_status_require_a_body()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson(route('statuses.store'), ['body' => '']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors' => ['body']
        ]);
    }

    /** @test */
    public function a_status_requires_a_minimun_length()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson(route('statuses.store'), ['body' => 'qwer']);

        $response->assertStatus(422);

        $response->assertJsonStructure([
            'message', 'errors' => ['body']
        ]);
    }
}

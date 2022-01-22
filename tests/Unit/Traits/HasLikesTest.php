<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Like;
use App\Models\User;
use App\Traits\HasLikes;
use App\Events\ModelLiked;
use App\Events\ModelUnliked;

class HasLikesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Schema::create('model_with_likes', function (Blueprint $table) {
            $table->id();
        });
    }

    /** @test */
    public function a_model_morph_many_likes()
    {
        $model = ModelWithLike::create();

        Like::factory()->create([
            'likeable_id' => $model->id,
            'likeable_type' => get_class($model)
        ]);

        $this->assertInstanceOf(Like::class, $model->likes->first());
    }

    /** @test */
    public function a_model_can_be_liked_and_unlike()
    {
        $model = ModelWithLike::create();

        $this->actingAs( User::factory()->create() );

        $model->like();

        $this->assertEquals(1, $model->likes()->count());

        $model->unlike();

        $this->assertEquals(0, $model->likes()->count());
    }

    /** @test */
    public function a_model_can_be_liked_once()
    {
        $model = ModelWithLike::create();

        $this->actingAs( User::factory()->create() );

        $model->like();

        $this->assertEquals(1, $model->likes()->count());

        $model->like();

        $this->assertEquals(1, $model->likes()->count());
    }

    /** @test */
    public function a_model_knows_if_it_has_been_liked()
    {
        $model = ModelWithLike::create();

        $this->assertFalse( $model->isLiked() );

        $this->actingAs( User::factory()->create() );

        $this->assertFalse( $model->isLiked() );

        $model->like();

        $this->assertTrue( $model->isLiked() );
    }

    /** @test */
    public function a_model_knows_how_many_likes_it_has()
    {
        $model = new ModelWithLike(['id' => 1]);
        $this->assertEquals(0, $model->likesCount());
        Like::factory()->count(2)->create([
            'likeable_id' => $model->id,         // int 1...
            'likeable_type' => get_class($model) //App\\Models\\Comment
        ]);
        $this->assertEquals(2, $model->likesCount());
    }

    /** @test */
    public function an_event_is_fired_when_a_model_is_liked()
    {
        Event::fake([ModelLiked::class]);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');
        $model = new ModelWithLike(['id' => 1]);

        $this->actingAs(User::factory()->create());

        $model->like();

        Event::assertDispatched(ModelLiked::class, function ($event) {
            $this->assertInstanceOf(ModelWithLike::class, $event->model);
            $this->assertEventChannelType('public', $event);
            $this->assertEventChannelName($event->model->eventChannelName(), $event);
            $this->assertDontBroadcastToCurrentUser($event);
            return true;
        });
    }

    /** @test */
    public function can_get_the_event_channel_name()
    {
        $model = new ModelWithLike([ 'id' => 1 ]);

        $this->assertEquals(
            "modelwithlikes.1.likes",
            $model->eventChannelName()
        );
    }

    /** @test */
    public function an_event_is_fired_when_a_model_is_unliked()
    {
        Event::fake([ModelUnliked::class]);
        Broadcast::shouldReceive('socket')->andReturn('socket-id');

        $this->actingAs(User::factory()->create());

        $model = ModelWithLike::create();

        $model->likes()->firstOrCreate([
            'user_id' => Auth::id()
        ]);

        $model->unlike();

        Event::assertDispatched(ModelUnliked::class, function ($event) {
            $this->assertInstanceOf(ModelWithLike::class, $event->model);
            $this->assertEventChannelType('public', $event);
            $this->assertEventChannelName($event->model->eventChannelName(), $event);
            $this->assertDontBroadcastToCurrentUser($event);
            return true;
        });
    }
}

class ModelWithLike extends Model
{
    use HasLikes;

    public $timestamps = false;

    protected $fillable = ['id'];
}



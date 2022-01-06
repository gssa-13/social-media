<?php

namespace Tests\Browser;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserCanLikeCommentsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @throws \Throwable
     */
    public function users_can_like_and_unlike_comments()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $comment) {
            $browser->loginAs($user)->visit('/')
                ->waitForText($comment->body)
                ->assertSeeIn('@comment-likes-count',0)
                ->press('@comment-like-btn')
                ->waitForText('You Like')
                ->assertSee('You Like')
                ->assertSeeIn('@comment-likes-count',1)

                ->press('@comment-like-btn')
                ->waitForText('Like')
                ->assertSee('Like')
                ->assertSeeIn('@comment-likes-count',0)
            ;
        });
    }
}

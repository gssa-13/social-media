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

    /**
     * @test
     * @throws \Throwable
     */
    public function users_can_see_likes_comments_in_real_time()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create();

        $this->browse(function (Browser $browser1, Browser $browser2) use ($user, $comment) {
            $browser1->visit('/');

            $browser2->loginAs($user)->visit('/')
                ->waitForText($comment->body)
                ->assertSeeIn('@comment-likes-count', 0)
                ->press('@comment-like-btn')
                ->waitForText('You Like')
            ;

            $browser1->assertSeeIn('@comment-likes-count', 1);

            $browser2->press('@comment-like-btn')
                ->waitForText('Like');

            $browser1->pause(1000)
                ->assertSeeIn('@comment-likes-count', 0);
        });
    }
}

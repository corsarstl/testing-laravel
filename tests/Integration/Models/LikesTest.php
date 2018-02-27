<?php

namespace Tests\Integraton\Models;

use App\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;


class LikesTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function userCanLikePost()
    {
        $post = factory(Post::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $post->like();

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => get_class($post)
        ]);

        $this->assertTrue($post->isLiked());
    }
    
    /** @test */
    public function userCanUnlikePost()
    {
        $post = factory(Post::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $post->like();
        $post->unlike();

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => get_class($post)
        ]);

        $this->assertFalse($post->isLiked());
    }
    
    /** @test */
    public function userMayTogglePostsLikeStatus()
    {
        $post = factory(Post::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $post->toggleLikeStatus();

        $this->assertTrue($post->isLiked());

        $post->toggleLikeStatus();

        $this->assertFalse($post->isLiked());
    }
    
    /** @test */
    public function postKnowsHowManyLikesItHas()
    {
        $post = factory(Post::class)->create();
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $post->toggleLikeStatus();

        $this->assertEquals(1, $post->likesCount);

    }
}

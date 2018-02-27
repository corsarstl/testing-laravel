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

    protected $post;

    public function setUp()
    {
        parent::setUp();

//        $this->post = factory(Post::class)->create();

        $this->post = createPost();

        $this->singIn();
    }

    /** @test */
    public function userCanLikePost()
    {
        $this->post->like();

        $this->assertDatabaseHas('likes', [
            'user_id' => $this->user->id,
            'likeable_id' => $this->post->id,
            'likeable_type' => get_class($this->post)
        ]);

        $this->assertTrue($this->post->isLiked());
    }
    
    /** @test */
    public function userCanUnlikePost()
    {
        $this->post->like();
        $this->post->unlike();

        $this->assertDatabaseMissing('likes', [
            'user_id' => $this->user->id,
            'likeable_id' => $this->post->id,
            'likeable_type' => get_class($this->post)
        ]);

        $this->assertFalse($this->post->isLiked());
    }
    
    /** @test */
    public function userMayTogglePostsLikeStatus()
    {
        $this->post->toggleLikeStatus();

        $this->assertTrue($this->post->isLiked());

        $this->post->toggleLikeStatus();

        $this->assertFalse($this->post->isLiked());
    }
    
    /** @test */
    public function postKnowsHowManyLikesItHas()
    {
        $this->post->toggleLikeStatus();

        $this->assertEquals(1, $this->post->likesCount);
    }
}

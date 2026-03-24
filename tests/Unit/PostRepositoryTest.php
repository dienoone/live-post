<?php

namespace Tests\Unit;

use App\Exceptions\GeneralJsonException;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_create(): void
    {
        # 1. define the goal
        # test if create() will actually create a record in the DB

        # 2. replicate the env / apply any restrictions if available
        $repository = $this->app->make(PostRepository::class);

        # 3. define the source of truth
        $payload = [
            'title' => 'test title',
            'body' => []
        ];

        # 4. Compare the result
        $result = $repository->create($payload);
        $this->assertSame($payload['title'], $result->title, 'Post created does not have the same title...');
    }

    public function test_update(): void
    {
        // Goal: make sure we can update a post using the update method...

        // env
        $repository = $this->app->make(PostRepository::class);
        $dummyPost = Post::factory(1)->create()->first();

        // source of truth
        $payload = [
            'title' => 'updated title'
        ];

        // compare
        $result = $repository->update($dummyPost, $payload);
        $this->assertSame($payload['title'], $result->title, 'Post updated does not have the same title');
    }

    public function test_delete_will_thrown_exception_when_delete_post_that_doesnt_exist(): void
    {
        // Goal: test if forceDelete() is working

        // env
        $repository = $this->app->make(PostRepository::class);
        $dummyPost = Post::factory(1)->make()->first();

        // compare
        $this->expectException(GeneralJsonException::class);
        $deleted = $repository->forceDelete($dummyPost);
    }

    public function test_delete(): void
    {
        // Goal: test if forceDelete() is working

        // env
        $repository = $this->app->make(PostRepository::class);
        $dummyPost = Post::factory(1)->create()->first();

        // compare
        $deleted = $repository->forceDelete($dummyPost);
        $founded = Post::find($dummyPost->id);

        $this->assertSame(null, $founded, 'Post is not deleted');
    }
}

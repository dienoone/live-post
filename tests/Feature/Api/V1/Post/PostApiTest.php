<?php

namespace Tests\Feature\Api\V1\Post;

use App\Events\Models\Post\PostCreated;
use App\Events\Models\Post\PostDeleted;
use App\Events\Models\Post\PostUpdated;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index(): void
    {
        // load data in db
        $posts = Post::factory(10)->create();
        $postIds = $posts->map(fn($post) => $post->id);

        // call index endpoint
        $response = $this->json('get', '/api/v1/posts');

        // assert status
        $response->assertStatus(200);

        // verify ids
        $data = $response->json('data');
        collect($data)->each(fn($post) => $this->assertTrue(in_array($post['id'], $postIds->toArray())));
    }

    public function test_show(): void
    {

        // load data in db
        $dummy = Post::factory()->create();

        // call show endpoint
        $response = $this->json('get', "/api/v1/posts/{$dummy->id}");

        // assert status
        $result = $response->assertStatus(200)->json('data');
        $this->assertEquals(data_get($result, 'id'), $dummy->id, 'Response ID not the same as mode id.');
    }

    public function test_create(): void
    {
        // env
        Event::fake();
        $dummy = Post::factory()->make();

        $response = $this->json('post', '/api/v1/posts', $dummy->toArray());
        Event::assertDispatched(PostCreated::class);

        $result = $response->assertStatus(201)->json('data');

        $result = collect($result)->only(array_keys($dummy->getAttributes()));

        $result->each(function ($value, $filed) use ($dummy) {
            $this->assertSame(data_get($dummy, $filed), $value, 'Fillable is not the same');
        });
    }

    public function test_update(): void
    {
        Event::fake();
        $dummy = Post::factory()->create();
        $payload = Post::factory()->make();

        $fillables = collect((new Post)->getFillable());

        $fillables->each(function ($toUpdate) use ($dummy, $payload) {
            $response = $this->json('put', "/api/v1/posts/{$dummy->id}", [
                $toUpdate => data_get($payload, $toUpdate)
            ]);

            $result = $response->assertStatus(200)->json('data');
            Event::assertDispatched(PostUpdated::class);

            $this->assertSame(data_get($payload, $toUpdate), data_get($dummy->refresh(), $toUpdate));
        });
    }

    public function test_delete(): void
    {
        Event::fake();
        $dummy = Post::factory()->create();

        $response = $this->json('delete', "/api/v1/posts/{$dummy->id}");
        Event::fake(PostDeleted::class);

        $result = $response->assertStatus(204);

        $this->expectException(ModelNotFoundException::class);
        Post::findOrFail($dummy->id);
    }
}

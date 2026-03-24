<?php

namespace App\Http\Controllers;

use App\Events\Models\User\UserCreated;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PostController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        event(new UserCreated(User::factory()->make()));
        $pageSize = $request->page_size ?? 20;
        $posts = Post::query()->paginate($pageSize);

        return $this->paginated(PostResource::collection($posts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return PostResource
     */
    public function store(Request $request, PostRepository $repository)
    {
        $created = $repository->create($request->only([
            'title',
            'body',
            'user_ids'
        ]));

        return $this->success(new PostResource($created), status: 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Post  $post
     * @return PostResource
     */
    public function show(Post $post)
    {
        return $this->success(new PostResource($post));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Post  $post
     * @return PostResource | JsonResponse
     */
    public function update(Request $request, Post $post, PostRepository $repository)
    {
        $post = $repository->update($post, $request->only([
            'title',
            'body',
            'user_ids',
        ]));
        return $this->success(new PostResource($post));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Post  $post
     * @return JsonResponse
     */
    public function destroy(Post $post, PostRepository $repository)
    {
        $post = $repository->forceDelete($post);
        return $this->success(message: 'Deleted successfully', status: 204);
    }
}

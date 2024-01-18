<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display Listing of posts with pagination
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $status = request()->query('status', 'drafted');
        $type = request()->query('type', POST::POST);

        $posts = Post::query()
            ->select('id', 'title', 'featured_image', 'created_at')
            ->where('post_type', $type)
            ->when($status == 'published' && $type == POST::POST, function (Builder $query) {
                return $query->where('published_at', '<=', now()->toDateTimeString())
                    ->latest('published_at');
            }, function (Builder $query) {
                return $query->where('published_at', '=', null)
                    ->latest();
            })
            ->paginate()
            ->onEachSide(1);

        return response()->json($posts);
    }

    /**
     * Create New Post boilarplate
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        $title = request()->input('title', "New Post " . time());
        $type = in_array(request()->input('type', 1), [1, 2, 3, 4]) ? request()->input('type', 1) : 1;

        $post = new Post([
            'title' => $title,
            'slug' => Str::slug($title, '-'),
            'post_type' => $type,
            'user_id' => 1
        ]);

        $post->save();

        return response()->json([
            'massage' => 'success',
            'id' => $post->id
        ]);
    }

    /**
     * Show single post resource for given ID
     *
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $post = Post::query()
            ->select('id', 'title', 'slug', 'featured_image', 'seo_title', 'seo_desc', 'body', 'published_at')
            ->when(request()->query('videos', 0) == 1, function (Builder $query) {
                return $query->with('videos:id');
            }, function (Builder $query) {
                return $query->with(['tags:id,title', 'categories:id,title']);
            })
            ->findOrFail($id);

        return response()->json([
            'post' => $post,
            'categories' => Post::query()->where('post_type', Post::CATEGORY)->get([
                'id',
                'title'
            ]),
            'tags' => Post::query()->where('post_type', Post::TAG)->get(['id', 'title']),
        ]);
    }

    /**
     * Store or Update Newly Created Post to Database
     *
     * @return JsonResponse
     */
    public function store(string $id, PostRequest $request): JsonResponse
    {
        $data = $request->validated();

        $post = Post::query()
            ->findOrFail($id);

        $post->fill($data)->save();

        if ($post->post_type == Post::POST) {
            $tagsToSync = collect($request->input('tags', []))->map(function ($tag) {
                return (string) $tag['id'];
            })->toArray();

            $catToSync = collect($request->input('categories', []))->map(function ($tag) {
                return (string) $tag['id'];
            })->toArray();

            $post->tags()->sync($tagsToSync);
            $post->categories()->sync($catToSync);
        }

        return response()->json(['massage' => 'success']);
    }

    /**
     * Attach videos to posts
     *
     * @param string $id
     * @return JsonResponse
     */
    public function attach(string $id): JsonResponse
    {
        $data = request()->validate([
            'videos' => 'array|required'
        ]);

        Post::find($id)
            ->videos()
            ->sync($data['videos']);

        return response()->json(['massage' => 'success']);
    }

    /**
     * Delete Post From Database (soft_delete)
     *
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $post = Post::query()
            ->findOrFail($id)
            ->delete();

        return response()->json(null, 204);
    }
}

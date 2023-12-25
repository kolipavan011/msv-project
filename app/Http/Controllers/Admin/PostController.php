<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Display Listing of posts with pagination
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = Post::query()
            ->select('id', 'title', 'featured_image', 'created_at')
            ->where('post_type', Post::POST)
            ->when(request()->query('status', 'draft') === 'published', function (Builder $query) {
                return $query->where('published_at', '<=', now()->toDateTimeString());
            }, function (Builder $query) {
                return $query->where('published_at', '=', null);
            })
            ->latest()
            ->paginate();

        return response()->json($posts);
    }

    /**
     * Create Post
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        return response()->json(['massage' => 'success']);
    }

    /**
     * Store Newly Created Post to Database
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
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

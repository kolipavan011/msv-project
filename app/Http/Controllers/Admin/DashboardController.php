<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::query()->where('post_type', Post::POST)->count();
        $category = Post::query()->where('post_type', Post::CATEGORY)->count();
        $tags = Post::query()->where('post_type', Post::TAG)->count();
        $pages = Post::query()->where('post_type', Post::PAGE)->count();

        return response()->json([
            'posts' => $posts,
            'categories' => $category,
            'tags' => $tags,
            'pages' => $pages,
        ]);
    }
}

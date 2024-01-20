<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Store uploaded file to related posts feature image
     *
     * @param string $id
     * @return JsonResponse
     */
    public function store(string $id): JsonResponse
    {
        $payload = request()->file();

        if (!$payload) {
            return response()->json(null, 400);
        }

        $file = reset($payload);
        $post = Post::query()->findOrFail($id);

        $path = $file->storeAs('public/images', $post->slug . '.' . $file->getClientOriginalExtension());
        $url = Storage::url($path);

        $post->featured_image = $url;
        $post->save();

        return response()->json(['massage' => 'success', 'path' => Storage::url($path)]);
    }

    /**
     * Delete image file linked to posts
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $post = Post::query()->findOrFail($id);

        if (!$post) {
            return response()->json(null, 400);
        }

        if (Storage::delete($post->featured_image)) {
            $post->featured_image = null;
            $post->save();
        }

        return response()->json(['massage' => 'success']);
    }
}

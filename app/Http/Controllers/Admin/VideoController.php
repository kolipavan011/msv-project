<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Folder;
use Illuminate\Http\JsonResponse;

class VideoController extends Controller
{
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
        $post = Video::query()
            ->findOrFail($id)
            ->delete();

        return response()->json(null, 204);
    }
}

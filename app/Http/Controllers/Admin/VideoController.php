<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Folder;
use Illuminate\Http\JsonResponse;

class VideoController extends Controller
{
    /**
     * Send Json response of folder and videos
     *
     * @return JsonResponse
     */
    public function index(string $id): JsonResponse
    {
        $folder = Folder::query()
            ->select('id', 'name as title')
            ->where('parent_id', $id)
            ->get();

        $videos = Video::query()
            ->select('id', 'title', 'path', 'poster', 'size', 'created_at')
            ->where('folder_id', $id)
            ->get();

        return response()->json([...$folder, ...$videos]);
    }

    /**
     * Create video entry in database
     * As downloaded from youtube
     * Save in storage
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        return response()->json(['massage' => 'success']);
    }

    /**
     * Update Videos metadata to Database
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        return response()->json(['massage' => 'success']);
    }

    /**
     * Delete Video From Database (soft_delete)
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

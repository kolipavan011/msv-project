<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Folder;
use Illuminate\Http\JsonResponse;

class FolderController extends Controller
{
    /**
     * Create Post
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        $folderName = request('name', 'folder-name');
        $folderParent = request('parent', Folder::ROOT);

        Folder::create([
            'name' => $folderName,
            'parent_id' => $folderParent,
        ])->save();

        return response()->json(['massage' => $folderName]);
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
        $post = Folder::query()
            ->findOrFail($id)
            ->delete();

        return response()->json(null, 204);
    }
}

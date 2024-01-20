<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Folder;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use YouTube\YouTubeDownloader;
use YouTube\Exception\YouTubeException;
use Illuminate\Support\Str;

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
        $input =  request()->all();
        $massage = "Success";

        $youtube = new YouTubeDownloader();

        try {
            $downloadOptions = $youtube->getDownloadLinks($input['videoId']);

            if ($downloadOptions->getAllFormats()) {
                $video = $downloadOptions->getFirstCombinedFormat()->url;
                $thumbnails = $youtube->getThumbnails($input['videoId'])['high'];

                $this->fetchVideo($thumbnails, $video, $input['title'], $input['keyword']);
            }
        } catch (YouTubeException $e) {
            $massage = $e->getMessage();
        }

        return response()->json(['massage' => $massage]);
    }

    /**
     * Update Videos metadata to Database
     *
     * @return JsonResponse
     */
    public function store(string $id): JsonResponse
    {
        $validatedData = request()->validate([
            'title' => 'required',
        ]);

        Video::query()
            ->where('id', $id)
            ->update([
                'title' => $validatedData['title']
            ]);

        return response()->json(['massage' => "success"]);
    }

    /**
     * Delete Video From Database (soft_delete)
     *
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $video = Video::query()
            ->findOrFail($id);

        if (Storage::delete($video->path)) {
            $video->delete();
        }

        return response()->json(null, 204);
    }

    /**
     * @param string id
     * @return JsonResponse
     */

    public function paste(string $id): JsonResponse
    {
        $data = request()->validate([
            'folders' => 'array|required'
        ]);

        Video::query()
            ->whereIn('id', $data['folders'])
            ->update([
                'folder_id' => $id
            ]);

        return response()->json(['massage' => 'success']);
    }

    /**
     * Get video and thumbnail from Youtube.com
     * Save to storage
     * 
     * @param string $id
     * @param string $url
     * @param string $title
     * @param string $keyword
     * @return boolean
     */
    private function fetchVideo(string $thumb, string $url, string $title, string $keyword): bool
    {
        $now = Carbon::now();
        $storagePath = "public/{$now->year}/{$now->month}/" . Str::slug($keyword) . "-" . time();
        $imgPath = $storagePath . '.jpg';
        $videoPath = $storagePath . '.mp4';

        $imageSize = Storage::put($imgPath, file_get_contents($thumb));
        $videoSize = Storage::put($videoPath, file_get_contents($url));

        if (!$imageSize && !$videoSize) {
            return false;
        }

        Video::create([
            'title' => $title,
            'path' => Storage::url($videoPath),
            'poster' => Storage::url($imgPath),
            'size' => $this->formatFilesize(Storage::size($videoPath)),
            'folder_id' => 1,
            'user_id' => 1,
        ])->save();

        return true;
    }

    /**
     * Convert to filesize units
     *
     * @param integer $size
     * @return void
     */
    private function formatFilesize(int $size)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    /**
     * Post Type identifier for a POST.
     *
     * @const int
     */
    public const POST = 1;

    /**
     * Post Type identifier for a CATEGORY.
     *
     * @const int
     */
    public const CATEGORY = 2;

    /**
     * Post Type identifier for a TAG.
     *
     * @const int
     */
    public const TAG = 3;

    /**
     * Post Type identifier for a PAGE.
     *
     * @const int
     */
    public const PAGE = 4;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be casted.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
    ];

    /**
     * Define users video relationship
     *
     * @return BelongsToMany
     */
    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'posts_videos', 'post_id', 'video_id');
    }

    /**
     * Define users tags relationship
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Self::class, 'posts_tags', 'post_id', 'tag_id');
    }

    /**
     * Define users categories relationship
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Self::class, 'posts_cat', 'post_id', 'cat_id');
    }
}

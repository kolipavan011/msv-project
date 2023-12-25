<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}

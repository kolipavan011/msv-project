<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    /**
     * Id for a Root Folder.
     *
     * @const int
     */
    public const ROOT = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'folders';

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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Conner\Tagging\Taggable;

class Bookmark extends Model
{
    use HasFactory;
    use Taggable;

    protected $fillable = [
        'name',
        'url',
        'description',
    ];

    /**
     * Get user that owns bookmark
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}

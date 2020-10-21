<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    /**
     * Get user that owns bookmark
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}

<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get user's bookmarks
     *
     */
    public function bookmarks()
    {
        return $this->hasMany('App\Models\Bookmark');
    }
    
    /**
     * Check to see if a user "own's" a bookmark.
     *
     * @param  mixed $bookmark
     * @return void
     */
    public function ownsBookmark(Bookmark $bookmark)
    {
        return $this->id === $bookmark->user->id;
    }
    
    /**
     * Create a bookmark for a user.
     *
     * @param  mixed $bookmark
     * @return void
     */
    public function createBookmark(Bookmark $bookmark)
    {
        $this->bookmarks()->create($bookmark->toArray());
    }
    
    /**
     * Update a user's bookmark
     *
     * @param  mixed $request
     * @param  mixed $bookmark
     * @return void
     */
    public function updateBookmark(Request $request, Bookmark $bookmark)
    {
        $bookmark->name = $request->input('name');
        $bookmark->url = $request->input('url');
        $bookmark->description = $request->input('description');

        $this->bookmarks()->save($bookmark);
    }

    /**
     * Delete a user's bookmark
     *
     * @param  mixed $bookmark
     * @return void
     */
    public function deleteBookmark(Bookmark $bookmark)
    {
        $bookmark->delete();
    }
}

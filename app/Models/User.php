<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use App\Models\Bookmark;
use Conner\Tagging\Model\Tag;

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
        'is_admin',
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
     * Check if user is admin or not.
     *
     * @return void
     */
    public function isAdmin()
    {
        return (bool) $this->is_admin;
    }

    /**
     * Get user's bookmarks
     *
     */
    public function bookmarks()
    {
        return $this->hasMany('App\Models\Bookmark')->orderBy('created_at', 'desc');
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
        return $this->bookmarks()->create($bookmark->toArray());
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

        return $this->bookmarks()->save($bookmark);
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
    
    /**
     * Get all user's bookmarks by tag.
     *
     * @param  mixed $tag
     * @return void
     */
    public function taggedBookmarks($tag)
    {
        return $this->bookmarks()
            ->withAnyTag($tag);
    }
    
    /**
     * Search user bookmarks
     *
     * @param  mixed $term
     * @return void
     */
    public function searchBookmarks($term)
    {
        $bookmarks = $this->bookmarks()
            ->leftJoin('tagging_tagged', function ($join) {
                $join->on('bookmarks.id', '=', 'tagging_tagged.taggable_id');
            })
            ->where(function ($query) use ($term) {
                $query->whereRaw("UPPER(bookmarks.name) LIKE '%" . strtoupper($term) . "%'")
                    ->orWhereRaw("UPPER(tagging_tagged.tag_name) LIKE '%" . strtoupper($term) . "%'");
            })
            ->groupBy('bookmarks.name')
            ->get(['bookmarks.id']);

        return $this->bookmarks()->whereIn('id', $bookmarks->pluck('id'));
    }

    /**
     * Search user's bookmarks by bookmark name
     *
     * @param  mixed $term
     * @return void
     */
    public function searchBookmarksByName($term)
    {
        $bookmarksByName = $this->bookmarks()
            ->whereRaw("UPPER(bookmarks.name) LIKE '%" . strtoupper($term) . "%'");
    }

    /**
     * Search user's bookmarks by bookmark tag
     *
     * @param  mixed $term
     * @return void
     */
    public function searchBookmarksByTag($term)
    {
        $tags = Tag::whereRaw("UPPER(name) LiKE '%" . strtoupper($term) . "%'")
            ->get()
            ->map(function ($tag) {
                return $tag->only(['name']);
            })
            ->toArray();

        $tagNames = [];
        foreach($tags as $tag) {
            if (!in_array($tag['name'], $tagNames)) {
                array_push($tagNames, $tag['name']);
            }
        }

        return $this->taggedBookmarks($tagNames);
    }
}

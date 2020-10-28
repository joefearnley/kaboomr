<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BookmarkTagController extends Controller
{
    /**
     * Display a list of bookmarks by tag
     *
     * @return \Illuminate\Http\Response
     */
    public function list($tag)
    {
        $bookmarks = Auth::user()->taggedBookmarks($tag);

        return view('bookmarks.taglist', [
            'tag' => $tag,
            'bookmarks' => $bookmarks
        ]);
    }
}

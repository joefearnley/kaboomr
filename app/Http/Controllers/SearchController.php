<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SearchController extends Controller
{
    public function index($term)
    {
        $bookmarks = Auth::user()->searchBookmarks($term)->paginate(20);

        return view('bookmarks.search-results', ['bookmarks' => $bookmarks]);
    }
}

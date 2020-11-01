<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SearchController extends Controller
{
    public function index($term)
    {
        //$bookmarks = Bookmark::search()

        
        $bookmarks = Bookmark::where('name', 'LIKE', '%' . $term . '%')->get();

        return view('bookmarks.search-results', ['bookmarks' => $bookmarks]);
    }
}

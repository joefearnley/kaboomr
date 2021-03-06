<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index($term)
    {
        $bookmarks = Auth::user()->searchBookmarks($term)->paginate(15);

        return view('bookmarks.search-results', [
            'term' => $term,
            'bookmarks' => $bookmarks
        ]);
    }
}

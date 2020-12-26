<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    /**
     * Display a listing of bookmarks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookmarks = Auth::user()->bookmarks()->paginate(15);
        $mostUsedTags = Auth::user()->mostUsedTags();

        return view('bookmarks.index', [
            'bookmarks' => $bookmarks,
            'mostUsedTags' => $mostUsedTags
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bookmarks.create');
    }

    /**
     * Create a new created bookmark.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:150',
            'url' => 'required|url',
        ]);

        $bookmark = Auth::user()->createBookmark(new Bookmark($request->all()));

        if ($request->tags) {
            $bookmark->tag(explode(',', $request->tags));
        }

        $request->session()->flash('success', 'Bookmark successfully been created!');

        return redirect('/bookmarks');
    }

    /**
     * Show the form for editing bookmarks.
     *
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function edit(Bookmark $bookmark)
    {
        if (Auth::user()->ownsBookmark($bookmark)) {
            return view('bookmarks.edit', ['bookmark' => $bookmark]);
        }

        abort(403);
    }

    /**
     * Update the specified bookmark.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bookmark $bookmark)
    {
        $request->validate([
            'name' => 'required|max:150',
            'url' => 'required|url',
        ]);

        Auth::user()->updateBookmark($request, $bookmark);

        $bookmark->untag();

        if (!empty($request->tags)) {
            $bookmark->retag(explode(',', $request->tags));
        }

        $request->session()->flash('success', 'Bookmark successfully been updated!');

        return redirect('/bookmarks');
    }

    /**
     * Delete a bookmark.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Bookmark $bookmark)
    {
        if (Auth::user()->ownsBookmark($bookmark)) {
            Auth::user()->deleteBookmark($bookmark);

            $request->session()->flash('success', 'Bookmark successfully been deleted!');

            return redirect('/bookmarks');
        }

        abort(403);
    }
}

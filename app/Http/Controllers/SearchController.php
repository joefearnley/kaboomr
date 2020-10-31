<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        echo'<pre>';
        var_dump($request->all());
        die();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index($term)
    {
        echo'<pre>';
        var_dump($term);
        die();
    }
}

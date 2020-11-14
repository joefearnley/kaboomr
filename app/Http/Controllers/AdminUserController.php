<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::paginate(15);

        return view('admin.users', ['users' => $users]);
    }
}

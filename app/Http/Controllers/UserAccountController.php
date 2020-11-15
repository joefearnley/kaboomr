<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('account.index', ['user' => $user]);
    }

    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|max:150',
        ]);

        $user = Auth::user();

        $user->name = $request->name;
        $user->save();

        $request->session()->flash('success', 'Name has been updated!');

        return redirect('/account');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users|email|max:150',
        ]);

        $user = Auth::user();
        $user->email = $request->email;
        $user->save();

        $request->session()->flash('success', 'Email has been updated!');

        return redirect('/account');
    }
}

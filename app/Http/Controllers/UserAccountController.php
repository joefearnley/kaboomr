<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserAccountController extends Controller
{
    /**
     * Show user account update forms.
     *
     * @return void
     */
    public function index()
    {
        $user = Auth::user();

        return view('account.index', ['user' => $user]);
    }

    /**
     * Update an account's name.
     *
     * @param  mixed $request
     * @return void
     */
    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|max:150',
        ]);

        $user = Auth::user();

        $user->name = $request->name;
        $user->save();

        // Mail::to($user->email)->send(new NewUserNotification($user));

        $request->session()->flash('success', 'Your account\'s Name has been updated!');

        return redirect('/account');
    }

    /**
     * Update an account email address.
     *
     * @param  mixed $request
     * @return void
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users|email|max:150',
        ]);

        $user = Auth::user();
        $user->email = $request->email;
        $user->save();

        $request->session()->flash('success', 'Your account\'s Email has been updated!');

        return redirect('/account');
    }
}

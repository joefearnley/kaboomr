<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserAccountUpdate;

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
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:150',
            'email' => 'required|email|max:150',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        Mail::to($user->email)
            ->send(new UserAccountUpdate($user));

        $request->session()->flash('success', 'Your user account has been updated!');

        return redirect('/account');
    }
}

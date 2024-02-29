<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function preference()
    {
        return Inertia::render('Preferences');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function addPref(Request $request)
    {
        $user = Auth::user();
        $user->update([
            'preferences' => $request->preferences,
        ]);
        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->password;
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'birthdate' => $request->birthdate,
            'password' => $request->password,
            'telephone' => $request->telephone,
            'seller' => $request->seller,
            'email' => $request->email,
            'isAdmin' => '0',
            'gender' => $request->gender,
        ]);
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'birthdate' => $request->birthdate,
            'telephone' => $request->telephone,
            'seller' => $request->seller,
            'email' => $request->email,
            'gender' => $request->gender,
        ]);
        // return Redirect::route('profile.edit');
    }

    public function getMail(Request $request)
    {
        $mail = User::where('email', $request->email)->get();
        $num = User::where('telephone', $request->telephone)->get();
        if (count($mail) != 0) {
            return json_encode('mail');
        } else if (count($num) != 0) {
            return json_encode('num');
        } else {
            return json_encode('good');
        }

    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
        //     'password' => ['required', 'confirmed', Rules\Password::defaults()],
        // ]);
            // dd($request);
            try {
                $user = User::create([
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'birthdate' => $request->birthdate,
                    'password' => Hash::make($request->password),
                    'telephone' => $request->telephone,
                    'seller' => $request->seller,
                    'email' => $request->email,
                    'isAdmin' => '0',
                    'gender' => $request->gender,
                    'preferences' => $request->preferences,
                ]);        
            } catch (\Throwable $th) {
                // return $th;
                return Inertia::render('Auth/Register', ['message' => $th->getMessage()]);
            }
        

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::PREF);
    }
}

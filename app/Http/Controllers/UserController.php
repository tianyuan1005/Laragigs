<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //Show Register/Create From
    public function create()
    {
        return view("users.register");
    }

    // Create New User
    public function store(Request $request)
    {
        $formFields = $request->validate([
            "name" => ["required", "string", "min:3"],
            "email" => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'min:6']
        ]);

        // Hash Password
        $formFields['password'] = bcrypt($formFields['password']);

        // Create User
        $user = User::create($formFields);

        // Login
        auth()->login($user);

        return redirect()->route('listings.index')->with('message', 'User created and logged in ');
    }
    // logout User
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerate();

        return redirect()->route('listings.index')->with('message', 'You have been logged out!');
    }
}

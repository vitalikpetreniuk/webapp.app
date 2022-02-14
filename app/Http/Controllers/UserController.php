<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function create()
    {
        return view('user.create');
    }

    public function userForm() {
//        $user = User::create([
//            'name' => 'webapp_admin',
//            'email' => 'petreniuk.ua@gmail.com',
//            'password' => Hash::make('2[x}h.PC'),
//        ]);
        return view('user.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        session()->flash('success', 'Successful registration');
        Auth::login($user);
        return redirect()->home();
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            return redirect()->home();
        }

        return redirect()->back()->with('error', 'Incorrect login or password');
    }

}

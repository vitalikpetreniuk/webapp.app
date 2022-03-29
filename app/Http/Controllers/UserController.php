<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use JsValidator;
use Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * Define your validation rules in a property in
     * the controller to reuse the rules.
     */
    private $validationRules = [
        'email' => 'required|email|exists:users',
        'password' => 'required',
    ];


    public function create()
    {
        /* TODO: регистрация пользователей */
        return view('user.create');
    }

    public function userForm(Request $request) {
//        $user = User::create([
//            'name' => 'webapp_admin',
//            'email' => 'petreniuk.ua@gmail.com',
//            'password' => Hash::make('lutsk123ns'),
//        ]);

        if (auth()->check()) return redirect()->home();

        $validator = JsValidator::make($this->validationRules);

        return view('user.login')->with([
            'validator' => $validator,
        ]);
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

        Auth::login($user);
        return redirect()->home();
    }

    public function login(Request $request) {

        $validation = Validator::make($request->all(), $this->validationRules);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            return redirect()->home();
        }

    }

}

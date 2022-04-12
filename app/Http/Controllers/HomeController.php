<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Вернуть главную страницу
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index() {
//        if (!auth()->check()) {
//            return redirect()->route('user.login');
//        }
        return view('home');
    }

    public function store(Request $request) {

    }
}

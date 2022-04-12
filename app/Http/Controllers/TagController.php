<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    /**
     * Получение массива tags для api
     * @return \Illuminate\Http\JsonResponse
     */
    public function list() {
        $user_id = Auth::id() ?? $_GET['user_id'] ?? 1;
        $select = DB::select("SELECT name FROM tags", );
        if (isset($select[0])) {
            return response()->json(array_column($select, 'name'));
        }else {
            return response()->json([]);
        }
    }
}

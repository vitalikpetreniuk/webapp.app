<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SourceController extends Controller
{
    public function listSources() {
        $user_id = Auth::id() ?? $_GET['user_id'] ?? 1;
        $select = DB::select("SELECT name, id FROM sources", );
        $data = [];
        foreach ($select as $item) {
            $data[$item->id] = $item->name;
        }
        return '['.json_encode($data).']';
    }
}

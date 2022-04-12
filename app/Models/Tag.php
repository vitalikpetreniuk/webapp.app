<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    use HasFactory;

    public function list() {
        $user_id = Auth::id() ?? $_GET['user_id'] ?? 1;
        $select = DB::select("SELECT name FROM tags", );
        $data = [];
        return response()->json($data);
    }
}

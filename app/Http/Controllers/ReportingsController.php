<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportingsController extends Controller
{
    public function index() {
        $from = Carbon::now()->subMonth()->startOfMonth();
        $to = Carbon::now()->subMonth()->endOfMonth();
        $request = "SELECT net_sales_amount AS sum FROM revenues WHERE date BETWEEN ? AND ? AND user_id = ?";
        $data = DB::select($request, [$from,  $to, Auth::id()]);
        return view('reportings/reportings', compact('data'));
    }
}

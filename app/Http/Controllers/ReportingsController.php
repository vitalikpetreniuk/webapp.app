<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportingsController extends Controller
{
    public function index() {
        $from = Carbon::now()->subMonth()->startOfMonth();
        $to = Carbon::now()->subMonth()->endOfMonth();
        $request = "SELECT net_sales_amount AS sum FROM revenues WHERE date BETWEEN '$from' AND '$to'";
        $data = DB::select($request);
        return view('reportings/reportings', compact('data'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Revenue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Source;

class AnalyticsController extends Controller
{
    public function index()
    {
        if (isset($_GET['from'])) {
            $from = Carbon::createFromFormat('m.Y', $_GET['from']);
        } else {
            $from = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        }

        if (isset($_GET['to'])) {
            $to = Carbon::createFromFormat('m.Y', $_GET['from']);
        } else {
//            $to = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');
        }


        return view('reportings/reportings', ['data' => $this->prepareAnalyticsData($from, $to)]);
    }

    public function prepareAnalyticsData($from, $to)
    {
        $expenses = ExpenseController::getAllExpenses($from, $to);
        $revenues = RevenueController::getAllRevenues($from, $to);

        foreach ($expenses as &$item) {
            $item->class = 'minus';
            if (!isset($item->amount)) {
                dd($item);
            }

            if (ExpenseController::isPercent($item)) {
                $item->amount = number_format($item->amount, 2, '.', ',') . '%';
            } else {
                $item->amount = '-$' . number_format($item->amount, 2, '.', ',');
            }
        }

        foreach ($revenues as &$item) {
            $item->class = 'plus';
            $item->amount = '+$' . number_format($item->amount, 2, '.', ',');
            $item->source = 'From file';
            $item->source_id = 1;
        }

        $merged = array_merge($expenses, $revenues);

        function cmp($a, $b)
        {
            return strcmp($a->date, $b->date);
        }

        usort($merged, function ($a, $b) {
            return strcmp($a->date, $b->date);
        });

        foreach ($merged as &$item) {
            $item->source = '';

            if (isset($item->source_id) && !isset($item->source)) {
                $item->source = Source::find((int)$item->source_id)->name;
            }

            $item->date = date_format(new \DateTime($item->date), 'd.m.Y');
        }

//        dd($merged);

        return $merged;
    }
}

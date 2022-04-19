<?php

namespace App\View\Components;

use App\Http\Controllers\ExpenseCalculationsController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RevenueController;
use App\Models\Revenue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Nav extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $from = Carbon::now()->subYear()->format('Y-m-d');
        $to = Carbon::now()->format('Y-m-d');
        /* Получение сумы растрат */
        $data = RevenueController::getYearNavDate($from, $to);

        foreach ($data as $key => $item) {

            $controller = new ExpenseCalculationsController(Carbon::createFromFormat('Y-m-d', $item->date), Carbon::createFromFormat('Y-m-d', $item->date));

            $item->total_marketing_costs = $controller->countTotalMarketingCosts();
            if ($item->total_marketing_costs == 3) {
                $item->total_marketing_costs = 0;
            }
            $item->net_profit = $controller->countMonthNetTotal($item->sum, $item->total_marketing_costs);
        }

        return view('components.nav', compact('data'));
    }
}

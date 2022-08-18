<?php

namespace App\View\Components;

use App\Http\Controllers\CalculationsController;
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
        /* TODO: Replace from and to with postgres min and max */
        $from = DB::selectOne('SELECT date FROM expenses ORDER BY date LIMIT 1');
        $from = isset($from->date) ? $from->date : null;
        $to = DB::selectOne('SELECT date FROM expenses ORDER BY date DESC LIMIT 1');
        $to = isset($to->date) ? $to->date : null;
        if (!$from || !$to) $data = [];
        if (!isset($data)) {
            /* Получение сумы растрат */
            $data = RevenueController::getYearNavDate($from, $to);

            foreach ($data as $key => $item) {

                $controller = new CalculationsController(Carbon::createFromFormat('Y-m-d', $item->date), Carbon::createFromFormat('Y-m-d', $item->date));

                $item->total_marketing_costs = $controller->countTotalMarketingCosts();
                if ($item->total_marketing_costs == 3) {
                    $item->total_marketing_costs = 0;
                }
                $item->net_profit = $controller->countMonthNetTotal($item->sum, $item->total_marketing_costs);
            }
        }

        return view('components.nav', compact('data'));
    }
}

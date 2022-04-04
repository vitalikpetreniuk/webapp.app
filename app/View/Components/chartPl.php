<?php

namespace App\View\Components;

use App\Http\Controllers\ExpenseCalculationsController;
use App\Http\Controllers\ExpenseController;
use Carbon\Carbon;
use Illuminate\View\Component;

class chartPl extends Component
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

    public function chartPL() {
        $controller = new ExpenseCalculationsController(Carbon::createFromFormat('Y-m-d', '2022-01-01'), Carbon::createFromFormat('Y-m-d', '2022-31-02'));
        $fixed_costs = $controller->getFixedExpensesTotal();
        $marketing_costs = $controller->getMonthAdSpendCostsTotal();
        $cogs = $controller->getCogs() / 100;

        $y = $fixed_costs / ((1 - $cogs) - $marketing_costs);

        $x = $marketing_costs;

        return ['fixed_costs' => $fixed_costs, 'marketing_costs' => $marketing_costs, 'cogs' => $cogs];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.chart-pl')->with($this->chartPL());
    }
}

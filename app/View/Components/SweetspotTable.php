<?php

namespace App\View\Components;

use App\Http\Controllers\ExpenseCalculationsController;
use Carbon\Carbon;
use Illuminate\View\Component;

class SweetspotTable extends Component
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

    protected function getController()
    {
        return new ExpenseCalculationsController(Carbon::createFromFormat('Y-m-d', '2022-01-01'), Carbon::createFromFormat('Y-m-d', '2022-31-01'));
    }

    public function getData()
    {
        $controller = $this->getController();

        $fixed_costs = $controller->getFixedExpensesTotal();
        $globalcogs = $controller->getCogs() / 100;

        $returned = [];

        foreach (range(0.01, 0.5, 0.01) as $cogs) {
            $revenue_needed = $fixed_costs / ($globalcogs - $cogs);
            $derivative = pow($fixed_costs / ($globalcogs - $cogs), 2);
            $returned[] = compact('cogs', 'revenue_needed', 'derivative');
        }

        for ($i = 1; $i < count($returned); $i++) {
            extract($returned[$i]);
            $old_derivative = $returned[$i - 1]['derivative'] - $cogs;
            $returned[$i]['derivative_rate_of_change'] = $derivative / $old_derivative - 1;
            $old_derivative_rate_of_change = $derivative / $old_derivative - 1;
            $returned[$i]['difference_per_step'] = $derivative_rate_of_change / $old_derivative_rate_of_change - 1;
        }

        foreach (range(0.01, 0.5, 0.01) as $cogs) {
            $revenue_needed = $fixed_costs / ($globalcogs - $cogs);
            $oldcogs = $cogs - 0.01;
            $old_revenue_needed = $fixed_costs / ($globalcogs - $oldcogs);

            $rev_rate_of_change_needed = $revenue_needed / $old_revenue_needed - 1;

            $derivative = pow($fixed_costs / ($globalcogs - $cogs), 2);
            $old_derivative = pow($fixed_costs / ($globalcogs - $oldcogs), 2);

            $derivative_rate_of_change = $derivative / $old_derivative - 1;
            $old_derivative_rate_of_change = $derivative / $old_derivative - 1;

            $difference_per_step =

            $returned[] = array(
                'marketing_costs' => $cogs,
                'revenue_needed' => $revenue_needed,
            );
        }

        return json_encode($returned);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.sweetspot-table');
    }
}

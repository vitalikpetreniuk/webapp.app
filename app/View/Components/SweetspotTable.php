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

        $globalcogs = 1 - $controller->getCogs();

        $returned = [];

        foreach (range(0.1, 0.45, 0.01) as $marketing_cost) {
            $revenue_needed = round($fixed_costs / ($globalcogs - $marketing_cost));
            $derivative = $fixed_costs / pow(($globalcogs - $marketing_cost), 2);
            $allowable_marketing_cost = $revenue_needed * $marketing_cost;
            $returned[] = compact('marketing_cost', 'revenue_needed', 'derivative', 'allowable_marketing_cost');
        }

        for ($i = 1; $i < count($returned); $i++) {
            extract($returned[$i]);
            $current = &$returned[$i];
            $prev = &$returned[$i - 1];
            $old_derivative = $prev['derivative'] - $marketing_cost;
            $old_revenue_needed = $prev['revenue_needed'];
            $current['derivative_rate_of_change'] = $derivative_rate_of_change = round(($derivative / $old_derivative - 1) * 100, 2);
            $current['rev_rate_of_change_needed'] = $rev_rate_of_change_needed = round(($revenue_needed / $old_revenue_needed - 1) * 100, 2);
            if (isset($prev['derivative_rate_of_change'])) {
                $current['old_derivative_rate_of_change'] = $old_derivative_rate_of_change = $prev['derivative_rate_of_change'];
                $current['difference_per_step'] = $difference_per_step = $derivative_rate_of_change - $old_derivative_rate_of_change;
            }
            if (isset($difference_per_step, $prev['difference_per_step'])) {
                $old_difference_per_step = $prev['difference_per_step'];
                $current['change_in_difference'] = $change_in_difference = round($difference_per_step - $old_difference_per_step, 2);
//                var_dump($marketing_cost, $difference_per_step - $old_difference_per_step);
//                echo '<hr>';
            }
            if (isset($prev['change_in_difference'])) {
                $old_change_in_difference = $prev['change_in_difference'];
//

                $current['optimal_coefficient'] = $optimal_coefficient = round($change_in_difference - $old_change_in_difference, 2);
                if ($optimal_coefficient < 0) {
                    $current['optimal_coefficient'] = $optimal_coefficient = 0;
                }
            }
        }

        return $returned;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.sweetspot-table', ['data' => $this->getData()]);
    }
}

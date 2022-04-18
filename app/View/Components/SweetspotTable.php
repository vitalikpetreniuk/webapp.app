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
        $startDate = isset($_GET['startDate']) ? Carbon::createFromFormat('M Y', $_GET['startDate'])->firstOfMonth() : Carbon::now()->subMonth()->firstOfMonth();
        $endDate = isset($_GET['endDate']) ? Carbon::createFromFormat('M Y', $_GET['endDate']) : Carbon::now()->subMonth()->lastOfMonth();
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        $controller = new ExpenseCalculationsController($this->startDate, $this->endDate);
        $this->fixed_costs = $controller->getFixedExpensesTotal(); //уже среднее значение

        $this->globalcogs = 1 - $controller->getCogs(); //уже среднее значение

        if ($this->endDate->year - $this->startDate->year == 0) {
            $this->duration = $this->endDate->month - $this->startDate->month + 1 ?: 1;
        } else {
            $this->duration = ($this->endDate->month - $this->startDate->month <= 1 ? $this->endDate->month - $this->startDate->month + 1 : 1) + ($this->endDate->year - $this->startDate->year) * 12;
        }
    }

    private function _getData()
    {
        $returned = [];

        foreach (range(0.1, 0.45, 0.01) as $marketing_cost) {
            $revenue_needed = round($this->fixed_costs / ($this->globalcogs - $marketing_cost));
            $derivative = $this->fixed_costs / pow(($this->globalcogs - $marketing_cost), 2);
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

    public function getData()
    {
        return $this->_getData();
    }

    /**
     * Подсчёт среднего значения за период
     * @param string $callback название метода
     * @return float|int - результат
     */
    private function loopSumAverage($callback)
    {
        $value = [];
        // делаем клоны чтобы не перезаписать дату в construct
        $obj1 = clone $this->startDate;
        $obj2 = clone $this->endDate;

        foreach (range(1, $this->duration) as $i) {
            if ($i == 1) {
                $startdate = $obj1->format('Y-m-d');
                $obj3 = clone $obj1;
                $enddate = $obj3->lastOfMonth()->format('Y-m-d');
            } else {
                $obj1->addMonths();
                $startdate = $obj1->format('Y-m-d');
                $obj3 = clone $obj1;
                $enddate = $obj3->lastOfMonth()->format('Y-m-d');
            }

            $value[] = call_user_func(array(__NAMESPACE__ . '\SweetspotTable', $callback), $startdate, $enddate);
        }

        dd($value);
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

<?php

namespace App\View\Components;

use App\Http\Controllers\ExpenseCalculationsController;
use App\Http\Traits\CogsTrait;
use App\Http\Traits\NumbersTrait;
use Carbon\Carbon;
use Illuminate\View\Component;

class SweetspotTable extends Component
{
    use CogsTrait, NumbersTrait;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $startDate = isset($_GET['startDate']) ? Carbon::createFromFormat('j M Y', '1 ' . $_GET['startDate'])->firstOfMonth() : Carbon::now()->subMonth()->firstOfMonth();
        $endDate = isset($_GET['endDate']) ? Carbon::createFromFormat('j M Y', '1 ' . $_GET['endDate']) : Carbon::now()->lastOfMonth();
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

    /**
     * Подсчёт данных для рендера
     * @return array данные аналитики для рендера
     */
    private function _getData()
    {
        $returned = [];

        if (!isset($this->globalcogs) || $this->fixed_costs == 1) return $returned;

        foreach (range(0.01, 0.44, 0.01) as $marketing_cost) {
            try {
                $revenue_needed = $this->fixed_costs / ($this->globalcogs - $marketing_cost);
                $derivative = $this->getDerivativeRate($this->fixed_costs, $this->globalcogs, $marketing_cost);
                $allowable_marketing_cost = $revenue_needed * $marketing_cost;
                $returned[] = compact('marketing_cost', 'revenue_needed', 'derivative', 'allowable_marketing_cost');
            } catch (\ErrorException $error) {
                continue;
            }
        }

        for ($i = 1; $i < count($returned); $i++) {
            extract($returned[$i]);
            $current = &$returned[$i];
            $prev = &$returned[$i - 1];
            $old_derivative = $prev['derivative'] - $marketing_cost;
            $old_revenue_needed = $prev['revenue_needed'];
            $current['derivative_rate_of_change'] = $derivative_rate_of_change = ($derivative / $old_derivative - 1) * 100;
            $current['rev_rate_of_change_needed'] = $rev_rate_of_change_needed = ($revenue_needed / $old_revenue_needed - 1) * 100;

            if (isset($prev['derivative_rate_of_change'])) {
                $current['old_derivative_rate_of_change'] = $old_derivative_rate_of_change = $prev['derivative_rate_of_change'];
                $current['difference_per_step'] = $difference_per_step = $derivative_rate_of_change - $old_derivative_rate_of_change;
            }

            if (isset($difference_per_step, $prev['difference_per_step'])) {
                $old_difference_per_step = $prev['difference_per_step'];
                $current['change_in_difference'] = $change_in_difference = $difference_per_step - $old_difference_per_step;
            }

            if (isset($prev['change_in_difference'])) {
                $old_change_in_difference = $prev['change_in_difference'];

                $current['optimal_coefficient'] = $optimal_coefficient = $change_in_difference - $old_change_in_difference;
                if ($optimal_coefficient < 0) {
                    $current['optimal_coefficient'] = $optimal_coefficient = 0;
                }
            }
        }

        return $returned;
    }

    /**
     * Данные аналитики для рендера
     * @return array данные аналитики для рендера
     */
    public function getData()
    {
        $data = $this->_getData();
        foreach ($data as &$item) {
            $item['revenue_needed'] = $this->basicDollarNumberFormat($item['revenue_needed']);
            if (isset($item['optimal_coefficient'])) {
                $item['optimal_coefficient'] = number_format($item['optimal_coefficient'], 5);
            }
            $item['allowable_marketing_cost'] = $this->basicDollarNumberFormat($item['allowable_marketing_cost']);
        }
        return $data;
    }

    /**
     * Получить Cost of goods sold за период
     * @return array массив значений за каждый месяц периода
     */
    private function getRangeCogs()
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

            $value[] = $this->getCogs($startdate, $enddate);

        }

        return $value;
    }

    /**
     * Получить fixed_costs за период
     * @return array массив значений за каждый месяц
     */
    private function getRangeFixedCosts()
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

            $value[] = $this->getFixedExpenses($startdate, $enddate);

        }

        return $value;
    }

    /**
     * Общая функция для проверки как дальше считать derivative_rate
     * две формулы одна на месяц другая на период
     * @param float|int $fixed_costs фиксированные расходы
     * @param float|int $globalcogs Cost of good sold
     * @param float|int $marketing_cost marketing_cost
     * @return float|int derivative_rate
     */
    public function getDerivativeRate($fixed_costs, $globalcogs, $marketing_cost)
    {
        if ($this->duration > 1) return $this->getRangeDerivativeRate($marketing_cost);
        return $this->getMonthDerivativeRate($fixed_costs, $globalcogs, $marketing_cost);
    }

    /**
     * @param float|int $fixed_costs фиксированные расходы
     * @param float|int $globalcogs Cost of goods sold
     * @param float|int $marketing_cost marketing_cost
     * @return float|int derivative_rate за период
     */
    public function getMonthDerivativeRate($fixed_costs, $globalcogs, $marketing_cost)
    {
        return $fixed_costs / pow(($globalcogs - $marketing_cost), 2);
    }

    /**
     * Получение derivative_rate за период по спецформуле
     * @param float|int $marketing_cost marketing_cost
     * @return float|int derivative_rate за период
     */
    private function getRangeDerivativeRate($marketing_cost)
    {
        $cogsarr = $this->getRangeCogs();
        $fixedarr = $this->getRangeFixedCosts();

        $num1 = array_reduce($cogsarr, function ($carry, $item) {
            $carry += 1 - $item;
            return $carry;
        });

        return array_sum($fixedarr) / pow(($num1 - $marketing_cost) / $this->duration, 2);
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

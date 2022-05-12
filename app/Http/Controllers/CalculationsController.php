<?php

namespace App\Http\Controllers;

use App\Http\Traits\AdSpendTrait;
use App\Http\Traits\AnalyticsTrait;
use App\Http\Traits\CogsTrait;
use App\Http\Traits\ExpenseRevenueTrate;
use App\Http\Traits\FixedExpensesTrait;
use App\Http\Traits\MarketingCostsTrait;
use App\Http\Traits\NetTotalTrait;
use App\Http\Traits\NumbersTrait;
use App\Http\Traits\RevenueTrait;
use App\Http\Traits\TotalMarketingCostsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalculationsController extends ExpenseController
{

    use MarketingCostsTrait, ExpenseRevenueTrate, NumbersTrait, NetTotalTrait, RevenueTrait, FixedExpensesTrait, RevenueTrait, AdSpendTrait, AnalyticsTrait, TotalMarketingCostsTrait, CogsTrait;

    public function __construct($from, $to)
    {
        $this->from = $from->firstOfMonth();
        $this->fromstring = $from->firstOfMonth()->format('Y-m-d');
        $this->to = $to->lastOfMonth();
        $this->tostring = $to->lastOfMonth()->format('Y-m-d');
        $this->expenses_table = 'expenses';
        $this->revenues_table = 'revenues';
        if ($this->to->year - $this->from->year == 0) {
            $this->duration = $this->to->month - $this->from->month + 1 ?: 1;
        } else {
            $this->duration = ($this->to->month - $this->from->month <= 1 ? $this->to->month - $this->from->month + 1 : 1) + ($this->to->year - $this->from->year) * 12;
        }

        $this->fixed_costs = $this->getFixedExpensesTotalSum();

        $this->globalcogs = $this->getCogs();
    }
    /**
     * Подсчёт среднего значения за период
     * @param string $callback название метода
     * @return float|int - результат
     */
    private function loopSumAverage($callback)
    {
        return ($this->loopSum($callback) / $this->duration) ?: 1;
    }

    /**
     * Подсчёт сумы значения за период
     * @param string $callback название метода
     * @return float|int - результат
     */
    private function loopSum($callback)
    {
        return array_sum($this->loop($callback)) ?: 1;
    }

    /**
     * Подсчет значений за каждый месяц периода
     * @param string $callback название метода
     * @return array массив значений
     */
    public function loop($callback)
    {
        $value = [];
        // делаем клоны чтобы не перезаписать дату в construct
        $obj1 = clone $this->from;
        foreach (range(1, $this->duration) as $i) {
            if ($i == 1) {
                $startdate = $obj1->format('Y-m-d');
                $obj3 = clone $obj1;
                $enddate = $obj3->lastOfMonth()->format('Y-m-d');
            } else {
                $obj1->addMonths(1);
                $startdate = $obj1->format('Y-m-d');
                $obj3 = clone $obj1;
                $enddate = $obj3->lastOfMonth()->format('Y-m-d');
            }

            $value[] = call_user_func(array(__NAMESPACE__ . '\CalculationsController', $callback), $startdate, $enddate);
        }

        return $value;
    }
}

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseCalculationsController extends ExpenseController
{

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
    }

    /**
     * Возращает расходы на маркетинг
     * @return float|int - расходы на маркетинг
     */
    private function _getMarketingCosts($from = false, $to = false)
    {
        $from = $from ?: $this->fromstring;
        $to = $to ?: $this->tostring;

        // Получение ad_spend числа за месяц
        $ad_spend = DB::select("SELECT sum(amount) as amount FROM $this->expenses_table WHERE expense_category_id = ? AND date BETWEEN ? AND ?", [1, $from, $to]);

        return isset($ad_spend[0]->amount) ? (int)$ad_spend[0]->amount : 0;
    }

    /**
     * Возращает расходы на маркетинг
     * @return float|int - расходы на маркетинг
     */
    public function getMarketingCosts()
    {
        return $this->loopSumAverage('_getMarketingCosts');
    }

    /**
     * Считает суму net_profit
     * @param $net_revenue - net_revenue
     * @param $marketing_costs - расходы на рекламу
     * @return float|int - число-сума
     */
    public function countMonthNetTotal($net_revenue, $marketing_costs)
    {
        return $net_revenue * (1 - $this->getMonthAffiliateCost()) - $this->getFixedExpensesTotal() - ($this->getCogs() * $net_revenue) - $marketing_costs * 1.05;
    }

    /**
     * Считает net_revenue за период
     * @return int|float - revenue
     */
    private function _getNetRevenue($from = false, $to = false)
    {
        $from = $from ?: $this->fromstring;
        $to = $to ?: $this->tostring;

        $amount = DB::select("SELECT SUM(amount) as amount FROM $this->revenues_table WHERE date BETWEEN ? AND ?", [$from, $to]);

        return isset($amount[0]->amount) ? $amount[0]->amount : 0;
    }

    /**
     * Считает net_revenue за период
     * @return int|float - revenue
     */
    public function getNetRevenue()
    {
        return $this->loopSumAverage('_getNetRevenue');
    }

    /**
     * Синоним к % of net revenue
     * @return float|int - % of net revenue
     */
    public function getMonthAffiliateCost()
    {
        return $this->getMonthPercentOfRevenue();
    }

    /**
     * Получает суму фиксированных расходов за месяц
     * $param $from - дата старта
     * $param $to - дата до
     * @return int|float - сума расходов
     */
    private function _getFixedExpensesTotal($from = false, $to = false)
    {
        $from = $from ?: $this->fromstring;
        $to = $to ?: $this->tostring;
        $fixed_costs = DB::select("SELECT SUM(amount) as amount FROM $this->expenses_table WHERE type_of_sum = ? AND date BETWEEN ? AND ?", [1, $from, $to]);
        return $fixed_costs ? (int)$fixed_costs[0]->amount : 0;
    }

    /**
     * Получает суму фиксированных расходов за период
     * @return int|float - средняя расходов
     */
    public function getFixedExpensesTotal()
    {
        return $this->loopSumAverage('_getFixedExpensesTotal');
    }

    /**
     * Получить массив expenses которые введены вручную (фиксированные расходы)
     * @return array массив объектов
     */
    public function getFixedExpensesStatements()
    {
        $fixed_statements = DB::select("SELECT * FROM $this->expenses_table WHERE type_of_sum = ? AND date BETWEEN ? AND ?", [1, $this->fromstring, $this->tostring]);
        return $fixed_statements ?: [];
    }

    /**
     * Получает Cost of Good Sold - себестоимость
     * @return float|int cost of good sold или ноль если не записано
     */
    private function _getCogs($from = false, $to = false)
    {
        $from = $from ?: $this->fromstring;
        $to = $to ?: $this->tostring;

        $cogs = DB::select("SELECT amount FROM $this->expenses_table WHERE type_variable = ? AND date BETWEEN ? AND ?", [1, $from, $to]);
        return $cogs ? (int)$cogs[0]->amount / 100 : 0;
    }

    /**
     * Получает Cost of Good Sold - себестоимость за период
     * @return float|int|null cost of good sold или ноль если не записано
     */
    public function getCogs()
    {
        return $this->loopSumAverage('_getCogs');
    }

    /**
     * Получить % of ad spend
     * @return float|int % of ad spend
     */
    private function _getMonthPercentOfAdSpend($from = false, $to = false)
    {
        $from = $from ?: $this->fromstring;
        $to = $to ?: $this->tostring;

        $percent_of_ad_spend = DB::select("SELECT amount FROM $this->expenses_table WHERE type_variable = ? AND date BETWEEN ? AND ?", [3, $from, $to]);
        return isset($percent_of_ad_spend[0]->amount) ? $percent_of_ad_spend[0]->amount / 100 : 0;
    }

    /**
     * Получить % of ad spend за период
     * @return float|int % of ad spend
     */
    public function getMonthPercentOfAdSpend()
    {
        return $this->loopSumAverage('_getMonthPercentOfAdSpend');
    }

    /**
     * Получить % of net revenue
     * @return float|int % of net revenue
     */
    private function _getMonthPercentOfRevenue($from = false, $to = false)
    {
        $from = $from ?: $this->fromstring;
        $to = $to ?: $this->tostring;

        $percent_of_revenue = DB::select("SELECT amount FROM $this->expenses_table WHERE type_variable = ? AND date BETWEEN ? AND ?", [2, $from, $to]);
        if (!isset($percent_of_revenue[0])) return 0;
        if ($percent_of_revenue[0]->amount >= 1) {
            return $percent_of_revenue[0]->amount / 100;
        } else {
            return $percent_of_revenue[0]->amount / 10;
        }
    }

    /**
     * Получить % of net revenue за период
     * @return float|int % of net revenue
     */
    public function getMonthPercentOfRevenue()
    {
        return $this->loopSumAverage('_getMonthPercentOfRevenue');
    }

    /**
     * Подсчёт сумы расходов на маркетинг
     * @return float|int - сума расходов на маркетинг
     */
    private function _countTotalMarketingCosts($from = false, $to = false)
    {
        $ad_spend = $this->getMarketingCosts();

        $percent_of_ad_spend = $this->getMonthPercentOfAdSpend();
        $percent_of_revenue = $this->getMonthPercentOfRevenue();

        return $ad_spend + ($ad_spend * $percent_of_ad_spend) + ($ad_spend * $percent_of_revenue);
    }

    /**
     * Подсчёт сумы расходов на маркетинг за период
     * @return float|int - сума расходов на маркетинг
     */
    public function countTotalMarketingCosts()
    {
        return $this->loopSumAverage('_countTotalMarketingCosts');
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
        $obj1 = clone $this->from;
        $obj2 = clone $this->to;
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

            $value[] = call_user_func(array(__NAMESPACE__ . '\ExpenseCalculationsController', $callback), $startdate, $enddate);
        }

        return (array_sum($value) / $this->duration) ?: 1;
    }
}

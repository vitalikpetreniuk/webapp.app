<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalculationsController extends ExpenseController
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
     * type_of_sum = 1 -fixed costs
     * type_of_sum = 2 - ad_spend costs
     * @return float|int - расходы на маркетинг
     */
    private function _getMarketingCosts($from = false, $to = false)
    {
        $from = $from ?: $this->fromstring;
        $to = $to ?: $this->tostring;

        // Получение ad_spend числа за месяц
        $ad_spend = DB::select("SELECT sum(amount) as amount FROM $this->expenses_table WHERE type_of_sum = ? AND date BETWEEN ? AND ?", [2, $from, $to]);

        return isset($ad_spend[0]->amount) ? (int)$ad_spend[0]->amount : 0;
    }

    /**
     * Возращает расходы на маркетинг (среднее)
     * @return float|int - расходы на маркетинг
     */
    public function getMarketingCosts()
    {
        return $this->loopSumAverage('_getMarketingCosts');
    }

    /**
     * Возращает расходы на маркетинг суму)
     * @return float|int - расходы на маркетинг
     */
    public function getMarketingCostsSum()
    {
        return $this->loopSum('_getMarketingCosts');
    }

    /**
     * Считает суму net_profit
     * @param $net_revenue - net_revenue
     * @param $marketing_costs - расходы на рекламу
     * @return float|int - число-сума
     */
    public function countMonthNetTotal($net_revenue, $marketing_costs)
    {
        if (!$marketing_costs) return 0;
        if (in_array(1, [$this->getFixedExpensesTotal(), $this->getFixedExpensesTotal(), $this->getCogs(), $marketing_costs], true)) return 0;

        $ad_spend_commission = $this->getMonthPercentOfAdSpend() + 1;
        return $net_revenue * (1 - $this->getMonthAffiliateCost()) - $this->getFixedExpensesTotal() - ($this->getCogs() * $net_revenue) - $this->getAdSpend() * $ad_spend_commission;
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
     * Считает net_revenue за период (сума)
     * @return int|float - revenue
     */
    public function getNetRevenueSum()
    {
        return $this->loopSum('_getNetRevenue');
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
     * Получает суму фиксированных расходов за период (среднее)
     * @return int|float - средняя расходов
     */
    public function getFixedExpensesTotal()
    {
        return $this->loopSumAverage('_getFixedExpensesTotal');
    }


    /**
     * Получает суму фиксированных расходов за период (суму)
     * @return int|float - средняя расходов
     */
    public function getFixedExpensesTotalSum()
    {
        return $this->loopSum('_getFixedExpensesTotal');
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
     * Получает Cost of Good Sold - себестоимость за период (среднее)
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
        return $this->_getMonthPercentOfAdSpend();
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
        return $this->_getMonthPercentOfRevenue();
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

        return $ad_spend + ($this->getNetRevenueSum() * $percent_of_revenue) + ($ad_spend * $percent_of_ad_spend);
    }

    /**
     * Подсчёт сумы расходов на маркетинг за период (среднее)
     * @return float|int - сума расходов на маркетинг
     */
    public function countTotalMarketingCosts()
    {
        return $this->loopSumAverage('_countTotalMarketingCosts');
    }

    /**
     * Подсчёт ad_spend за период
     * @return float|int сума ad_spend
     */
    public function getAdSpend() {
        $ad_spend = DB::select("SELECT sum(amount) as amount FROM $this->expenses_table WHERE from_file = true AND date BETWEEN ? AND ?", [$this->fromstring, $this->tostring]);

        return isset($ad_spend[0]->amount) ? $ad_spend[0]->amount : 0;
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

<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseCalculationsController extends ExpenseController
{

    public function __construct($from, $to)
    {
        $this->from = $from->firstOfMonth()->format('Y-m-d');
        $this->to = $to->lastOfMonth()->format('Y-m-d');
        $this->expenses_table = 'expenses';
        $this->revenues_table = 'revenues';
    }

    /**
     * Возращает расходы на маркетинг
     * @return float|int - расходы на маркетинг
     */
    public function getMonthMarketingCosts()
    {
        // Получение ad_spend числа за месяц
        $ad_spend = DB::select("SELECT sum(amount) as amount FROM $this->expenses_table WHERE expense_category_id = ? AND date BETWEEN ? AND ?", [1, $this->from, $this->to]);

        return isset($ad_spend[0]) ? (int)$ad_spend[0]->amount : 0;
    }

    /**
     * Считает суму net_profit
     * @param $net_revenue - net_revenue
     * @param $marketing_costs - расходы на рекламу
     * @return float|int - число-сума
     */
    public function countMonthNetTotal($net_revenue, $marketing_costs)
    {
        return $net_revenue * (1 - $this->getMonthAffiliateCost()) - $this->getFixedExpensesTotal() -($this->getCogs() * $net_revenue)-$marketing_costs * 1.05;
    }

    /**
     * Считает net_revenue за период
     * @return int|float - revenue
     */
    public function getMonthNetRevenue()
    {
        $amount = DB::select("SELECT SUM(amount) as amount FROM $this->revenues_table WHERE date BETWEEN ? AND ?", [$this->from, $this->to]);

        return isset($amount[0]) ? $amount[0]->amount : 0;
    }

    /**
     * Синоним к % of net revenue
     * @return float|int - % of net revenue
     */
    public function getMonthAffiliateCost() {
        return $this->getMonthPercentOfRevenue();
    }

    /**
     * Получает суму фиксированных расходов
     * @return int|float - сума расходов
     */
    public function getFixedExpensesTotal()
    {
        $fixed_costs = DB::select("SELECT SUM(amount) as amount FROM $this->expenses_table WHERE type_of_sum = ? AND date BETWEEN ? AND ?", [1, $this->from, $this->to]);
        return $fixed_costs ? (int)$fixed_costs[0]->amount : 0;
    }

    /**
     * Получить массив expenses которые введены вручную (фиксированные расходы)
     * @return array массив объектов
     */
    public function getFixedExpensesStatements()
    {
        $fixed_statements = DB::select("SELECT * FROM $this->expenses_table WHERE type_of_sum = ? AND date BETWEEN ? AND ?", [1, $this->from, $this->to]);
        return $fixed_statements ?: [];
    }

    /**
     * Получает Cost of Good Sold - себестоимость
     * @return float|int cost of good sold или ноль если не записано
     */
    public function getCogs()
    {
        $cogs = DB::select("SELECT amount FROM $this->expenses_table WHERE type_variable = ? AND date BETWEEN ? AND ?", [1, $this->from, $this->to]);
        return $cogs ? (int)$cogs[0]->amount / 100 : 0;
    }


    /**
     * Получить % of ad spend
     * @return float|int % of ad spend
     */
    public function getMonthPercentOfAdSpend()
    {
        $percent_of_ad_spend = DB::select("SELECT amount FROM $this->expenses_table WHERE type_variable = ? AND date BETWEEN ? AND ?", [3, $this->from, $this->to]);
        return isset($percent_of_ad_spend[0]) ? $percent_of_ad_spend[0]->amount / 100 : 0;
    }

    /**
     * Получить % of net revenue
     * @return float|int % of net revenue
     */
    public function getMonthPercentOfRevenue()
    {
        $percent_of_revenue = DB::select("SELECT amount FROM $this->expenses_table WHERE type_variable = ? AND date BETWEEN ? AND ?", [2, $this->from, $this->to]);
        if (!isset($percent_of_revenue[0])) return 0;
        if ($percent_of_revenue[0]->amount >= 1) {
            return $percent_of_revenue[0]->amount / 100;
        }else {
            return $percent_of_revenue[0]->amount / 10;
        }
    }

    /**
     * Подсчёт сумы расходов на маркетинг
     * @return float|int - сума расходов на маркетинг
     */
    public function countMonthTotalMarketingCosts()
    {
        $ad_spend = $this->getMonthMarketingCosts();

//        dd($ad_spend);
        $percent_of_ad_spend = $this->getMonthPercentOfAdSpend();
        $percent_of_revenue = $this->getMonthPercentOfRevenue();

        return $ad_spend + ($ad_spend * $percent_of_ad_spend) + ($ad_spend * $percent_of_revenue);
    }
}

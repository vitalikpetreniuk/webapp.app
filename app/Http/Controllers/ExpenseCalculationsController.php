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

    public function countMonthExpensesTotal()
    {
        return DB::select("SELECT expense_category_id, amount, date, type_of_sum, type_variable FROM $this->expenses_table WHERE date BETWEEN ? AND ?", [$this->from, $this->to]);
    }

    public function getMonthAdSpendCosts()
    {
        // Получение ad_spend числа за месяц
        $ad_spend = DB::select("SELECT sum(amount) as amount FROM $this->expenses_table WHERE expense_category_id = ? AND date BETWEEN ? AND ?", [1, $this->from, $this->to]);

        return isset($ad_spend[0]) ? (int)$ad_spend[0]->amount : 0;
    }

    public function countMonthNetTotal()
    {
        $expenses = $this::countMonthExpensesTotal();

        $net_revenue = $this::getMonthNetRevenue();

        $net_total = $net_revenue;

        $ad_spend = $this::getMonthAdSpendCosts();

        foreach ($expenses as $expense) {
            $amount = $expense->amount;

            $percent_from_ad_spend = in_array($expense->type_of_sum, [2]) || in_array($expense->type_variable, [1, 2]);
            $percent_from_net_revenue = in_array($expense->type_of_sum, [3]) || in_array($expense->type_variable, [3]);

            if ($percent_from_ad_spend) {
                $net_total = $net_total - ($amount * $ad_spend / 100);
            } elseif ($percent_from_net_revenue) {
                $net_total = $net_total - ($amount * $net_revenue / 100);
            } else {
                $net_total = $net_total - $amount;
            }
        }

        $net_total = $net_total - $ad_spend;

        return $net_total;
    }

    public function getMonthNetRevenue()
    {
        $amount = DB::select("SELECT SUM(amount) as sum FROM $this->revenues_table WHERE date BETWEEN ? AND ?", [$this->from, $this->to]);

        return isset($amount[0]) ? $amount[0]->sum : 0;
    }

    public function getFixedExpensesTotal()
    {
        $fixed_costs = DB::select("SELECT SUM(amount) FROM $this->expenses_table WHERE type_of_sum = ? AND date BETWEEN ? AND ?", [1, $this->from, $this->to]);
        return $fixed_costs ? (int)$fixed_costs[0]->sum : 0;
    }

    public function getCogs()
    {
        $cogs = DB::select("SELECT amount FROM $this->expenses_table WHERE type_variable = ? AND date BETWEEN ? AND ?", [1, $this->from, $this->to]);
        return $cogs ? (int)$cogs[0]->amount : 0;
    }


    public function getMonthPercentOfAdSpend()
    {
        return DB::select("SELECT amount FROM $this->expenses_table WHERE type_of_sum = ? AND date BETWEEN ? AND ?", [3, $this->from, $this->to]);
    }

    public function getMonthPercentOfRevenue()
    {
        return DB::select("SELECT amount FROM $this->expenses_table WHERE type_of_sum = ? AND date BETWEEN ? AND ?", [2, $this->from, $this->to]);
    }

    public function countMonthTotalMarketingCosts()
    {
        $ad_spend = $this->getMonthAdSpendCosts();
        $percent_of_ad_spend = $this->getMonthPercentOfAdSpend();
        if (isset($percent_of_ad_spend[0])) {
            $percent_of_ad_spend = $percent_of_ad_spend[0]->amount;
        }else {
            $percent_of_ad_spend = 0;
        }
        $percent_of_revenue = $this->getMonthPercentOfRevenue();
        if (isset($percent_of_revenue[0])) {
            $percent_of_revenue = $percent_of_revenue[0]->amount;
        }else {
            $percent_of_revenue = 0;
        }

        return $ad_spend + ($ad_spend * $percent_of_ad_spend) + ($ad_spend * $percent_of_revenue);
    }
}

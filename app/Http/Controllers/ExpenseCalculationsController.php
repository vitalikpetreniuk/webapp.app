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
    }

    public function countMonthExpensesTotal()
    {
        return DB::select('SELECT expense_category_id, amount, date, type_of_sum, type_variable FROM expenses WHERE date BETWEEN ? AND ?', [$this->from, $this->to]);
    }

    public function countMonthAdSpendCosts()
    {
        // Получение ad_spend числа за месяц
        $ad_spend = DB::select('SELECT sum(amount) as amount FROM expenses WHERE expense_category_id = ? AND date BETWEEN ? AND ?', [1, $this->from, $this->to]);

        return isset($ad_spend[0]) ? $ad_spend[0]->amount : 0;
    }

    public function countMonthNetTotal()
    {
        $expenses = $this::countMonthExpensesTotal();

        $net_revenue = $this::getMonthNetRevenue();

        $net_total = $net_revenue;

        $ad_spend = $this::countMonthAdSpendCosts();

        foreach ($expenses as $expense) {
            $amount = $expense->amount;

            $percent_from_ad_spend = in_array($expense->type_of_sum, [2]) || in_array($expense->type_variable, [1, 2]);
            $percent_from_net_revenue = in_array($expense->type_of_sum, [3]) || in_array($expense->type_variable, [3]);

            if ($percent_from_ad_spend) {
                $net_total = $net_total - ($amount * $ad_spend / 100);
            } elseif ($percent_from_net_revenue) {
                $net_total = $net_total - ($amount * $net_revenue / 100);
            }else {
                $net_total = $net_total - $amount;
            }
        }

        $net_total = $net_total - $ad_spend;

        return $net_total;
    }

    public function getMonthNetRevenue()
    {
        $amount = DB::select("SELECT SUM(net_sales_amount) as sum FROM revenues WHERE date BETWEEN ? AND ?", [$this->from, $this->to]);

        return isset($amount[0]) ? $amount[0]->sum : 0;
    }
}

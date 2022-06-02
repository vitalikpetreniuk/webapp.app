<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;

trait CogsTrait
{
    private $expenses_table = 'expenses';

    /**
     * Получает Cost of Good Sold - себестоимость
     * @return float|int cost of good sold или ноль если не записано
     */
    public function getRangeCogs($from = false, $to = false)
    {
        $cogs = DB::select("SELECT amount FROM $this->expenses_table WHERE type_variable = ? AND date BETWEEN ? AND ?", [1, $from, $to]);
        return isset($cogs[0]->amount) ? (int)$cogs[0]->amount / 100 : 0;
    }

    /**
     * Получает суму фиксированных расходов за месяц
     * $param $from - дата старта
     * $param $to - дата до
     * @return int|float - сума расходов
     */
    public function getFixedExpenses($from = false, $to = false)
    {
        $fixed_costs = DB::select("SELECT SUM(amount) as amount FROM $this->expenses_table WHERE type_of_sum = ? AND date BETWEEN ? AND ?", [1, $from, $to]);
        return isset($fixed_costs[0]->amount) ? (int)$fixed_costs[0]->amount : 0;
    }

    /**
     * Получает Cost of Good Sold - себестоимость за период (среднее)
     * @return float|int|null cost of good sold или ноль если не записано
     */
    public function getCogs()
    {
        if ($this->duration > 1) {
            return $this->getSweetspotCogs();
        }
        return $this->loopSumAverage('getRangeCogs');
    }

    /**
     * Cogs спец формула для sweetspot
     * @return float|int число cogs
     */
    public function getSweetspotCogs()
    {
        $loopedcogs = $this->loop('getRangeCogs');
        $loopedrevenue = $this->loop('_getNetRevenue');

        if(!$this->getNetRevenueSum()) return 0;

        return (array_sum(array_map(function ($el1, $el2) {
                return $el1 * $el2;
            }, $loopedcogs, $loopedrevenue)) / $this->getNetRevenueSum());
    }

}

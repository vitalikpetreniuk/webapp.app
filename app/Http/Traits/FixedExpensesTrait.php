<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait FixedExpensesTrait
{
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
}

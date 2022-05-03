<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait NumbersTrait
{
    /**
     * Форматирование сумы с знаком долара и округлением
     * @return string отформатированная сума
     */
    public function basicDollarNumberFormat($num) {
        $newnum = abs($num);
        $newnum = number_format($newnum, 0, '.', ',');
        if ($num < 0) {
            return '-$' . $newnum;
        }
        return $newnum . '$';
   }
}

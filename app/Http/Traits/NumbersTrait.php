<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use NumberFormatter;

trait NumbersTrait
{
    /**
     * Форматирование сумы с знаком долара и округлением
     * @param int|float $num число для форматирования
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

    /**
     * @throws \Exception
     */
    public function basicNumberParse($num) {
       $fmt = new NumberFormatter( 'de_DE', NumberFormatter::DECIMAL );
       $fmt2 = new NumberFormatter( 'en_EN', NumberFormatter::DECIMAL );
           if($fmt2->parse($num)) {
               return $fmt2->parse($num);
           }else if($fmt->parse($num)) {
               return $fmt->parse($num);
           }else {
               throw new \Exception('Unknown number format');
           }
       }
}

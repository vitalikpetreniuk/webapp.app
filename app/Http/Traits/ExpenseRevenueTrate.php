<?php
namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ExpenseRevenueTrate {
    /**
     * Проверка на формат даты
     * @param string $stringdate строка даты
     * @return Carbon|false объект даты
     */
    private function parseUserFileInputDate($stringdate)
    {
        if (strpos($stringdate, '/') > 0) {
            $sep = '/';
        } elseif (strpos($stringdate, '-') > 0) {
            $sep = '-';
        } elseif (strpos($stringdate, '.') > 0) {
            $sep = '.';
        } else {
            return false;
        }

        return Carbon::createFromFormat("n" . $sep . "j" . $sep . "Y", $stringdate);
    }

    /**
     * Технический метод чтобы проверить что все елементы массива пустые
     * @param array $array массив для проверки
     * @return bool результат проверки
     */
    private function containsOnlyNull(array $array): bool
    {
        foreach ($array as $value) {
            if ($value !== null) {
                return false;
            }
        }
        return true;
    }
}

<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ExpenseRevenueTrate
{
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

    /**
     * Bulk удаление импортированыех с файла записей
     * @param string $date дата в формате месяц.год
     * @return void
     */
    public function deleteImported($date)
    {
        $date = Carbon::createFromFormat('j.m.Y', '1.' . $date);
        $from = $date->firstOfMonth()->format('Y-m-d');
        $to = $date->lastOfMonth()->format('Y-m-d');
        DB::delete("DELETE from $this->table WHERE from_file = true AND date BETWEEN ? AND ?", [$from, $to]);
    }
}

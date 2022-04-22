<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Revenue;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Source;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        $startDate = isset($_GET['startDate']) ? Carbon::createFromFormat('M Y', $_GET['startDate'])->firstOfMonth() : Carbon::now()->subMonth()->firstOfMonth();
        $endDate = isset($_GET['endDate']) ? Carbon::createFromFormat('M Y', $_GET['endDate']) : Carbon::now()->subMonth()->lastOfMonth();
        $this->from = $startDate;
        $this->to = $endDate;

        if ($endDate->year - $startDate->year == 0) {
            $this->duration = $endDate->month - $startDate->month + 1 ?: 1;
        } else {
            $this->duration = ($endDate->month - $startDate->month <= 1 ? $endDate->month - $startDate->month + 1 : 1) + ($endDate->year - $startDate->year) * 12;
        }
    }


    /**
     * Темплейт страницы аналитики
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('reportings/reportings', ['data' => $this->prepareAnalyticsData()]);
    }


    /**
     * Генерация данных для страницы аналитики
     * @return array массив expenses и revenues
     */
    public function prepareAnalyticsData()
    {
        return [...$this->getAllExpenses(), ...$this->getRevenues()];
    }

    /**
     * Получить массив expense которые введены вручную
     * @return array массив данных
     */
    public function getManualStatements()
    {
        return DB::select("SELECT expenses.*, TO_CHAR(date, 'Month') AS \"month\", EXTRACT(year from date) AS \"YEAR\" FROM expenses WHERE from_file = false AND date BETWEEN ? AND ?", [$this->from, $this->to]);
    }

    /**
     * Подготовить expense для отображения
     * @param $item - raw массив с б.д
     * @return object - готовый expense
     * @throws \Exception
     */
    public function beautifyExpense($item): object
    {
        if (isset($item->source_id) && !isset($item->source)) {
            $item->source = Source::find((int)$item->source_id)->name;
        }

        if (ExpenseController::isPercent($item)) {
            $item->amount = number_format($item->amount, 2, '.', ',') . '%';
        } else {
            $item->amount = '-$' . number_format($item->amount, 2, '.', ',');
        }

        $item->type = '';

//        if(!isset($_GET['startDate'], $_GET['endDate']) || $_GET['startDate'] === $_GET['endDate']) {
        $item->editable = true;
//        }else {
//            $item->editable = false;
//        }

        if ($item->type_of_sum === 1) {
            $item->type = 'Fixed costs';
        } else if ($item->type_variable === 1) {
            $item->type = 'Cost of good sold';
        } else if ($item->type_variable === 2) {
            $item->type = 'Affiliate commission';
        } else if ($item->type_variable === 3) {
            $item->type = 'Ad spend commission';
        }

        $item->type .= '<br> (' . trim($item->month) . ')';

        $item->class = 'minus';

        $tags = $this->getExpenseTag($item->id);
        if ($tags) {
            $item->tags = implode(', ', $tags->toArray());
        }

        $item->date = date_format(new \DateTime($item->date), 'd.m.Y');

        return $item;
    }

    public function getExpenseTag($expense_id)
    {
        $tags = DB::select('SELECT DISTINCT tags.name FROM tags_expenses INNER JOIN tags ON tags_expenses.tag_id = tags.id WHERE tags_expenses.expense_id = ?', [$expense_id]);
        return isset($tags[0]) ? collect($tags)->pluck('name') : false;
    }

    /**
     * Подготовить revenue для отображения
     * @param $item - raw revenue
     * @return object|false - готовый revenue
     */
    public function beautifyRevenue($item, $month): object
    {
        $item->type = "Revenue ($month)";
        $item->class = 'plus';
        $item->editable = false;

        return $item;
    }

    /**
     * Получить все expenses
     * @return array массив всех expenses
     * @throws \Exception
     */
    public function getAllExpenses()
    {
        $manuals = $this->getManualStatements();

        foreach ($manuals as &$item) {
            $item = $this->beautifyExpense($item);
        }

        return $manuals;
    }

    /**
     * Получить все revenues
     * @return array[] массив revenues
     */
    private function _getRevenues()
    {
        $value = [];
        // делаем клоны чтобы не перезаписать дату в construct
        $obj1 = clone $this->from;
        $obj2 = clone $this->to;
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

            $revenues = DB::select("SELECT sum(amount) as amount FROM revenues WHERE date BETWEEN ? AND ?", [$startdate, $enddate]);
            if ((isset($revenues[0]->amount))) {
                $value[] = $this->beautifyRevenue($revenues[0], $obj1->format('F'));
            }
        }

        return $value;
    }

    /**
     * Получить все expenses
     * @return array[]|false - массив или если нету то false
     */
    public function getRevenues()
    {
        return $this->_getRevenues();
    }
}

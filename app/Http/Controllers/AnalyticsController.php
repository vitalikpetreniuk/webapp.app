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
        if (isset($_GET['from'])) {
            $this->from = Carbon::createFromFormat('m.Y', $_GET['from']);
        } else {
            $this->from = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        }

        if (isset($_GET['to'])) {
            $this->to = Carbon::createFromFormat('m.Y', $_GET['from']);
        } else {
//            $to = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
            $this->to = Carbon::now()->format('Y-m-d');
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
     * @return array - массив expenses и revenues
     */
    public function prepareAnalyticsData()
    {
        return [...$this->getAllExpenses(), ...$this->getAllRevenues()];
    }

    /**
     * Получить массив expense которые введены вручную
     * @return array - массив
     */
    public function getManualStatements()
    {
        return DB::select('SELECT * FROM expenses WHERE from_file = false AND date BETWEEN ? AND ?', [$this->from, $this->to]);
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

        $item->editable = true;

        if ($item->type_of_sum === 1) {
            $item->type = 'Fixed costs';
        }else if ($item->type_variable === 1) {
            $item->type = 'Cost of good sold';
        }else if ($item->type_variable === 2) {
            $item->type = 'Affiliate commission';
        }else if ($item->type_variable === 3) {
            $item->type = 'Ad spend commission';
        }

        $item->class = 'minus';

        $item->date = date_format(new \DateTime($item->date), 'd.m.Y');

        return $item;
    }

    /**
     * Подготовить revenue для отображения
     * @param $item - raw revenue
     * @return object - готовый revenue
     */
    public function beautifyRevenue($item): object
    {
        $item->type = 'Revenue';
        $item->class = 'plus';
        $item->editable = false;

        return $item;
    }

    /**
     * Получить все expenses
     * @return array - массив всех expenses
     * @throws \Exception
     */
    public function getAllExpenses() {
        $manuals = $this->getManualStatements();

        foreach ($manuals as &$item) {
            $item = $this->beautifyExpense($item);
        }

        return $manuals;
    }

    /**
     * Получить все expenses
     * @return array[]|false - массив или если нету то false
     */
    public function getAllRevenues() {
        $revenues = DB::select("SELECT sum(amount) as amount FROM revenues WHERE date BETWEEN ? AND ?", [$this->from, $this->to]);
        return isset($revenues[0]) ? [$this->beautifyRevenue($revenues[0])] : false;
    }
}

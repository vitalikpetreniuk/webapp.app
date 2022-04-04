<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\FutureExpense;
use App\Models\Revenue;
use App\Models\Source;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\This;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExpenseController extends Controller
{
    public function val()
    {
        $validation = \Validator::make($this::validationRules());

        $validation->sometimes(['files'], 'required|mimes:xls,xlsx', function ($input) {
            return $input->expensecategory == 1;
        });

        $validation->sometimes(['sum', 'tag', 'monthpicker2'], 'required', function ($input) {
            return $input->expensecategory == 2;
        });

        $validation->sometimes(['monthpicker3'], 'required', function ($input) {
            return $input->expensecategory == 3;
        });

        $validation->sometimes(['cost-of-good-sold'], 'required', function ($input) {
            return $input->cat3input == 1;
        });

        $validation->sometimes(['affiliate-commission'], 'required|max:100', function ($input) {
            return $input->cat3input == 2;
        });

        $validation->sometimes(['ad-spend-commission'], 'required|max:100', function ($input) {
            return $input->cat3input == 3;
        });

        //expensetype

        $validation->sometimes(['cost-of-good-sold'], 'required', function ($input) {
            return $input->cat3input == 1;
        });


        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }

        return $validation;

        // do store stuff
    }

    public function store(Request $request)
    {
        /* TODO: валидация формата файла */
        if ($request->hasFile('files') && $request->file('files')->isValid()) {
            $request->file('files');
            $path = $request->file('files')->store('xlsx');

            $this->expenseCategory1($path);
        } elseif ($request->input('expensecategory') == 2) {
            $this->expenseCategory2($request);
        } elseif ($request->input('expensecategory') == 3) {
            $this->expenseCategory3($request);
        }
    }

    public function update(Request $request, Expense $expense) {
        $expense->update($request->all());
    }

    public function delete(Expense $expense) {
        $expense->delete();
    }

    public static function validationRules()
    {
        return [];
    }

    private function containsOnlyNull(array $array): bool
    {
        foreach ($array as $value) {
            if ($value !== null) {
                return false;
            }
        }
        return true;
    }

    protected function expenseCategory1($path)
    {
        $reader = IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load(storage_path("app\\" . $path));
        $sheet = $spreadsheet->getActiveSheet()->toArray();

        try {
            foreach ($sheet as $row) {
                if ($this->containsOnlyNull($row)) break;
                $date = new \DateTime();
                $date = $date::createFromFormat('m/j/Y', $row[0]);
                if (!$date) continue;
                Expense::create(
                    [
                        'date' => $date,
                        'user_id' => Auth::id(),
                        'amount' => $row[6],
                        'expense_category_id' => 1,
                        'from_file' => true
                    ]
                );
            }
            $send['success'] = true;
            $send['message'] = 'XLSX was successfully imported';
            echo json_encode($send);
        } catch (\Exception $exception) {
            $send = [];
            $message = 'Failed importing xlsx file';
            switch ($exception->getCode()) {
                case 23505 :
                    $message = 'This date has already been imported';
            }
            if (App::environment('local')) {
                $send['debugcode'] = $exception->getCode();
                $send['debugmessage'] = $exception->getMessage();
            }

            $send['success'] = false;
            $send['message'] = $message;

            echo json_encode($send);
        }
    }

    protected function expenseCategory2($request)
    {
        $date = Carbon::createFromFormat('m.y', $request->input('monthpicker2'))->firstOfMonth();

        if (!$request->input('source')) {
            $source = null;
        } else {
            $source = Source::firstOrCreate(["name" => $request->input('source')], ["user_id" => Auth::id()])->id;
        }

        try {
            $expense = [
                'date' => $date,
                'amount' => $request->input('amount'),
                'user_id' => Auth::id(),
                'source_id' => $source,
                'comment' => $request->input('comment'),
                'type_of_sum' => $request->input('expensetype'),
            ];

            if ($source) $expense['source_id'] = $source;
            $created = Expense::create($expense);

            $send['success'] = true;
            $send['message'] = 'Expense was created';
        } catch (\Exception $exception) {
            $send = [];

            $send['code'] = $exception->getCode();

            if (App::environment('local')) {
                $send['debugmessage'] = $exception->getMessage();
            }

            $send['success'] = false;
            $send['message'] = $exception->getMessage();

        }

        if ($request->input('repeated2')) {
            $arr = [
                'expense_id' => $created->id,
                'period' => $request->input('repeated2'),
                'user_id' => Auth::id()
            ];

            FutureExpense::insert($arr);

        }

        echo json_encode($send);
    }

    protected function expenseCategory3($request)
    {
        $date = Carbon::createFromFormat('m.y', $request->input('monthpicker3'))->firstOfMonth();

        $amount = $request->input('cost-of-good-sold') ?? $request->input('affiliate-commission') ?? $request->input('ad-spend-commission');

        try {
            Expense::create(
                [
                    'date' => $date,
                    'amount' => $amount,
                    'user_id' => Auth::id(),
                    'type_variable' => $request->input('cat3input')
                ]
            );

            $send['success'] = true;
            $send['message'] = 'Expense was created';
            echo json_encode($send);
        } catch (\Exception $exception) {
            $send = [];

            if (App::environment('local')) {
                $send['debugcode'] = $exception->getCode();
                $send['debugmessage'] = $exception->getMessage();
            }

            $send['success'] = false;
            $send['message'] = $exception->getMessage();

            echo json_encode($send);
        }

    }

    public static function getAllExpenses($from, $to)
    {
        return DB::select('SELECT * FROM expenses WHERE date BETWEEN ? AND ?', [$from, $to]);
    }

    public function getSingle(Expense $expense) {
        return $expense;
    }

    public static function isPercent($expense) {
        $percent_from_ad_spend = in_array($expense->type_of_sum, [2]) || in_array($expense->type_variable, [1, 2]);
        $percent_from_net_revenue = in_array($expense->type_of_sum, [3]) || in_array($expense->type_variable, [3]);

        return $percent_from_ad_spend || $percent_from_net_revenue;
    }
}

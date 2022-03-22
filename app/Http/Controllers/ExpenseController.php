<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Revenue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExpenseController extends Controller
{
    public function val(Request $request)
    {
        $validation = \Validator::make($request->all(), $this::validationRules());

        $validation->sometimes(['files'], 'required|mimes:xls,xlsx', function ($input) {
            return $input->expensecategory == 1;
        });

        $validation->sometimes(['sum', 'tag'], 'required', function ($input) {
            return $input->expensecategory == 2;
        });

        $validation->sometimes(['cost-of-good-sold'], 'required', function ($input) {
            return $input->cat3input == 1;
        });

        $validation->sometimes(['affiliate-commission'], 'required', function ($input) {
            return $input->cat3input == 2;
        });

        $validation->sometimes(['ad-spend-commission'], 'required', function ($input) {
            return $input->cat3input == 3;
        });

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }

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
                        'fixed' => true,
                        'user_id' => Auth::id(),
                        'amount' => $row[6],
                        'expense_category_id' => 1
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
        $date = Carbon::createFromFormat('d.y', $request->input('monthpicker2'));
        if ($request->input('expensetype') == 2) {
            $fixed = true;
        } else {
            $fixed = false;
        }

        if ($request->input('source')) {
            $source = DB::table('source')->where('name', $request->input('source'))->value('id');
        }

        try {
            $expense =  [
                'date' => $date,
                'fixed' => $fixed,
                'amount' => $request->input('amount'),
                'user_id' => Auth::id(),
                'expense_category_id' => $request->input('expensetype'),
            ];

            $source ? $expense['source_id'] = $source : null;
            Expense::create($expense);

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

    protected function expenseCategory3($request)
    {
        $date = Carbon::createFromFormat('d.y', $request->input('monthpicker2'));
        if ($request->input('expensetype') == 2) {
            $fixed = true;
        } else {
            $fixed = false;
        }

        try {
            Expense::create(
                [
                    'date' => $date,
                    'fixed' => $fixed,
                    'amount' => $request->input('amount'),
                    'user_id' => Auth::id(),
                    'expense_category_id' => $request->input('expensetype')
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
}

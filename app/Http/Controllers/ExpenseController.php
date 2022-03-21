<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
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

            $this->parseUploadedXlsx($path);
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

    protected function parseUploadedXlsx($path)
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
                Revenue::create(
                    [
                        'date' => $date,
                        'number_of_items_sold' => $row[1],
                        'number_of_orders' => $row[2],
                        'average_net_sales_amount' => $row[3],
                        'coupon_amount' => $row[4],
                        'shipping_amount' => $row[5],
                        'gross_sales_amount' => $row[6],
                        'net_sales_amount' => $row[7],
                        'refund_amount' => $row[8],
                        'user_id' => Auth::id(),
                    ]
                );
            }
            $send['success'] = true;
            echo json_encode([
                'message' => 'XLSX was successfully imported'
            ]);
        } catch (\Exception $exception) {
            $send = [];
            $message = 'Failed importing xlsx file';
            switch ($exception->getCode()) {
                case 23505 : $message = 'This date has already been imported';
            }
            if (App::environment('local')) {
                $send['debugcode'] = $exception->getCode();
            }
            $send['success'] = false;
            $send['message'] = $message;

            echo json_encode($send);
        }
    }
}

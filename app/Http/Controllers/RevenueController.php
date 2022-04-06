<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Http\Request;

class RevenueController extends Controller
{

    public function val(Request $request)
    {
        $validation = \Validator::make($request->all(), $this::validationRules());

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
        return [
            'files' => 'required|mimes:xls,xlsx',
        ];
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

    private function parseUserFileInputDate($stringdate)
    {
        if (strpos($stringdate, '/') > 0 ) {
            $sep = '/';
        }elseif (strpos($stringdate, '-') > 0) {
            $sep = '-';
        }elseif (strpos($stringdate, '.') > 0) {
            $sep = '.';
        }else {
            return false;
        }

        return Carbon::createFromFormat("n".$sep."j".$sep."Y", $stringdate);
    }

    protected function parseUploadedXlsx($path)
    {
        $reader = IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load(storage_path("app\\" . $path));
        $sheet = $spreadsheet->getActiveSheet()->toArray();

        try {
            foreach ($sheet as $row) {
                if ($this->containsOnlyNull($row)) break;
                $date = $this->parseUserFileInputDate($row[0]);
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
                        'amount' => $row[7],
                        'refund_amount' => $row[8],
                        'user_id' => Auth::id(),
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
                case 23505 : $message = 'This date has already been imported';
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

    public static function getYearNavDate($from, $to)
    {
        return DB::select("SELECT TO_CHAR(date, 'Month') AS \"month\", EXTRACT(year from date) AS \"YEAR\", MIN(date) as date, SUM(amount) as sum FROM revenues WHERE date BETWEEN ? AND ? GROUP BY 1, 2", [$from, $to]);
    }

    public static function getAllRevenues($from, $to) {
        return DB::select('SELECT * FROM revenues WHERE date BETWEEN ? AND ?', [$from, $to]);
    }

    public function update(Revenue $revenue, Request $request) {
        $revenue->update($request->all());
    }

    public function getSingle(Revenue $revenue) {
        return $revenue;
    }

    public function delete(Revenue $revenue) {
        $revenue->delete();
    }
}

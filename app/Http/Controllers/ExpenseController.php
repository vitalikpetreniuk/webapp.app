<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Revenue;
use App\Models\Source;
use App\Models\Tag;
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

    /**
     * Стартовая обработка отправленной формы
     * @param Request $request объект запроса
     * @return void
     */
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

    /**
     * обновление expense по api
     * @param Request $request объект запроса
     * @param Expense $expense - объект expense для обновления
     * @return void
     */
    public function update(Request $request, Expense $expense)
    {
        $expense->update($request->all());
    }

    /**
     * удаление expense по api
     * @param Expense $expense - объект expense для обновления
     * @return void
     */
    public function delete(Expense $expense)
    {
        $expense->delete();
    }

    public static function validationRules()
    {
        return [];
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
     * Если форма expense отправлена с 1 категорией (с файлом для импортирования)
     * @param string $path путь к файлу
     * @return void
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    protected function expenseCategory1($path)
    {
        $reader = IOFactory::createReader("Xlsx");
        /* TODO: fix bug with slashes */
        $spreadsheet = $reader->load(storage_path("app" . DIRECTORY_SEPARATOR . $path));
        $sheet = $spreadsheet->getActiveSheet()->toArray();

        try {
            foreach ($sheet as $row) {
                if ($this->containsOnlyNull($row)) break;
                $date = $this->parseUserFileInputDate($row[0]);
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
            $send = $this->userUnderstandableError($exception, true);
        }
    }

    /**
     * Если форма expense отправлена с 2 категорией
     * @param Request $request объект запроса
     * @return void
     */
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

            if ($request->input('tags')) {
                $tags = explode(',', $request->input('tags'));

                foreach ($tags as $name) {
                    $tag = Tag::firstOrCreate([
                        'name' => $name,
                    ], ['user_id' => Auth::id()]);

                    DB::table('tags_expenses')->insert([
                        'expense_id' => $created->id,
                        'tag_id' => $tag->id,
                    ]);
                }
            }

            $send['success'] = true;
            $send['message'] = 'Expense was created';
        } catch (\Exception $exception) {
            $send = $this->userUnderstandableError($exception);
        }

//        if ($request->input('repeated2')) {
//            $arr = [
//                'expense_id' => $created->id,
//                'period' => $request->input('repeated2'),
//                'user_id' => Auth::id()
//            ];
//
//            FutureExpense::insert($arr);
//
//        }

        echo json_encode($send);
    }

    /**
     * Если форма expense отправлена с 3 категорией
     * @param Request $request объект запроса
     * @return void
     */
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
            echo json_encode($this->userUnderstandableError($exception));
        }

    }

    /**
     * Получить все expense за период
     * @param string $from - дата начала
     * @param string $to - дата конца
     * @return array - массив объектов expense
     */
    public static function getAllExpenses($from, $to)
    {
        return DB::select('SELECT * FROM expenses WHERE date BETWEEN ? AND ?', [$from, $to]);
    }

    /**
     * Получение айтема expense для api
     * @param Expense $expense
     * @return Expense
     */
    public function getSingle(Expense $expense)
    {
        return $expense;
    }

    /**
     * Является ли число в этой категории процентом?
     * @param $expense - объект expense
     * @return bool true если значение есть процентом
     */
    public static function isPercent($expense)
    {
        $percent_from_ad_spend = in_array($expense->type_of_sum, [2]) || in_array($expense->type_variable, [1, 2]);
        $percent_from_net_revenue = in_array($expense->type_of_sum, [3]) || in_array($expense->type_variable, [3]);

        return $percent_from_ad_spend || $percent_from_net_revenue;
    }

    /**
     * Генерация ошибки для отправки для юзера
     * @param $exception - объект ошибки
     * @param $isfileimport - отобразить ли ошибку что файл не загружен
     * @return array - массив с данными ошибки
     */
    public function userUnderstandableError($exception, $isfileimport = false): array
    {
        $send = [];
        switch ($exception->getCode()) {
            case 23505 :
                $message = 'This date has already been imported';
                break;
            case 23502 :
                $message = 'Amount is missing';
                break;
        }

        if (!$message && $isfileimport) {
            $message = 'Failed importing xlsx file';
        } elseif (!$message && !$isfileimport) {
            $message = 'Failed to execute';
        }

        $send['debugcode'] = $exception->getCode();
        $send['debugmessage'] = $exception->getMessage();

        $send['success'] = false;
        $send['message'] = $message;

        return $send;
    }
}

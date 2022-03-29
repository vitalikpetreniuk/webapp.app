<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';

    protected $fillable = ['date', 'user_id', 'amount', 'expense_category_id', 'source_id', 'comment', 'type_of_sum', 'type_variable', 'from_file'];
}

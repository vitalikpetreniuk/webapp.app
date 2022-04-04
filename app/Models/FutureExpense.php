<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FutureExpense extends Model
{
    use HasFactory;

    protected $table = 'future_expenses';

    protected $fillable = ['period', 'expense_id', 'last_confirmed_at', 'user_id'];

    const UPDATED_AT = 'last_confirmed_at';
}

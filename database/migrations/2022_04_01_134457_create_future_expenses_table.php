<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFutureExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('future_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Expense::class);
            $table->foreignIdFor(\App\Models\User::class);
            $table->integer('period');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('last_confirmed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('future_expenses');
    }
}

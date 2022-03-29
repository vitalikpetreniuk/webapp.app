<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class);
            $table->foreignIdFor(\App\Models\ExpenseCategory::class)->nullable();
            $table->foreignIdFor(\App\Models\Source::class)->nullable();
            $table->decimal('amount')->default(0.00);
            $table->text('comment')->nullable();
            $table->date('date');
            $table->integer('type_of_sum')->nullable()->default(0);
            $table->integer('type_variable')->nullable()->default(0);
            $table->boolean('from_file')->default(false);
            $table->boolean('expense')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}

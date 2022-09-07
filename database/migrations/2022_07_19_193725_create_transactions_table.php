<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('transaction_type', ['local','foreign']);
            $table->enum('transaction_category', ['paid','received']);
            $table->integer('customer_id');
            $table->text('description');
            $table->decimal('amount',20,2)->nullable();
            $table->decimal('exchange_rate',20,6)->nullable();
            $table->decimal('paid_amount',20)->nullable();
            $table->decimal('received_amount',20)->nullable();
            $table->integer('currency_id')->nullable();
            $table->integer('transactoion_with')->nullable();
            $table->tinyInteger('daily_transaction')->nullable()->comment('0: false, 1: true');
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('cancel_transaction')->default(0);
            $table->integer('amount_status')->default(0);
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
        Schema::dropIfExists('transactions');
    }
}

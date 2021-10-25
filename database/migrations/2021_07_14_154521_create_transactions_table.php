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
    // id_user, id_contest, id_criteria, id_criteria_contents, status, id_bank, payment_image, id_ticket, 
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('id_contest');
            $table->integer('id_criteria');
            $table->integer('id_criteria_contents');
            $table->string('status'); //unpaid, waiting, done
            $table->integer('id_bank')->nullable();
            $table->string('payment_image')->nullable();
            $table->string('id_ticket')->nullable();
            $table->integer('price')->nullable();
            $table->string('exp_time')->nullable();
            $table->string('contestant_number')->nullable();
            $table->string('contestant_block')->nullable();
            
            $table->integer('created_by')->nullable()->default(1); //from admin 0
            
            $table->string('bird_name_contestant')->nullable();
            $table->string('paid_at')->nullable();
            $table->string('cancelled_at')->nullable();
            $table->string('confirmed_at')->nullable();

            $table->integer('soft_delete')->default(0);
            
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

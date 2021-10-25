<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailContestCriteriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_contest_criterias', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_contest');
            $table->integer('registration_fee');
            $table->string('criteria_name');
            $table->integer('fixed_price')->default(0);
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
        Schema::dropIfExists('detail_contest_criterias');
    }
}

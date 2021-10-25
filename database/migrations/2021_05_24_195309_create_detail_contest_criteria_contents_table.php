<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailContestCriteriaContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_contest_criteria_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_detail_contest_criteria');
            $table->unsignedInteger('id_contest');
            $table->string('bird_name');
            $table->string('participants');
            $table->string('jury_code');
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
        Schema::dropIfExists('detail_contest_criteria_contents');
    }
}

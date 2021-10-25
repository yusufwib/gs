<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailContestCriteriaContentChampionPrizesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_contest_criteria_content_champion_prizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_detail_contest_criteria');
            $table->string('champion_prize');
            $table->string('champion_title');
            
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
        Schema::dropIfExists('detail_contest_criteria_content_champion_prizes');
    }
}

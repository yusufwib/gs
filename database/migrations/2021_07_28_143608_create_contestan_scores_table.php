<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestanScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contestan_scores', function (Blueprint $table) {
            $table->id();

            $table->integer('id_user');
            $table->integer('id_contest');
            $table->integer('id_criteria');
            $table->integer('id_criteria_contents');

            $table->integer('id_jury');
            $table->integer('id_transaction')->nullable();

            $table->integer('score_irama_lagu_roll')->nullable()->default(0);
            $table->integer('score_irama_lagu_tembak')->nullable()->default(0);
            $table->integer('score_durasi')->nullable()->default(0);
            $table->integer('score_volume')->nullable()->default(0);
            $table->integer('score_gaya')->nullable()->default(0);
            $table->integer('score_fisik')->nullable()->default(0);
            $table->double('score', 8, 2)->default(0);
            $table->string('score_description')->nullable()->default(0);

            $table->string('contestant_number')->nullable(); 
            $table->string('contestant_block')->nullable();

            $table->string('koncer_selected')->nullable()->default(0);

            $table->string('koncer')->nullable()->default(0);
            $table->string('koncer_position')->nullable()->default(0);
            $table->integer('soft_delete')->default(0);
            
            $table->string('winner_position')->nullable()->default(0);
            $table->string('is_winner')->nullable()->default(0);
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
        Schema::dropIfExists('contestan_scores');
    }
}

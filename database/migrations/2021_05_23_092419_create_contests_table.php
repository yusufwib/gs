<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contests', function (Blueprint $table) {
            $table->id();

            $table->string('name'); 
            $table->string('city');
            $table->date('start_register');
            // $table->date('end_register');
            $table->date('contest_date');
            $table->string('contest_time');
            $table->string('location_name');
            $table->text('location_address');
            $table->text('contest_terms');
            $table->string('contest_status'); //upcoming, past events
            $table->integer('drafted'); //0,1
            $table->integer('id_template_number')->nullable(); //0,1
            $table->integer('soft_delete')->default(0);
            $table->integer('is_open')->default(1);
            $table->integer('ready_to_jugde')->default(0);
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
        Schema::dropIfExists('contests');
    }
}

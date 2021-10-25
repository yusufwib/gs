<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('role_id'); // jury, admin, user, 0 = from admin
            $table->string('verified')->default(0);
            $table->text('avatar')->nullable();
            $table->string('otp')->nullable();
            $table->string('otp_reset_password')->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('password_show')->nullable();

            $table->integer('soft_delete')->default(0);
            $table->text('verified_jury')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('userable_id');
            $table->string('userable_type');
            $table->string('name_en');
            $table->string('name_ar')->nullable(false);
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('role')->unsigned()->nullable(false);
            $table->integer('number');
            $table->string('national_id', '14')->unique()->nullable(false);
            $table->string('address', '250');
            $table->string('phone', '13');
            $table->date('DOB');
            $table->boolean('gender')->comment('0 for male, 1 for female');
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

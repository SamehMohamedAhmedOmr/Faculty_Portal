<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpenCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('open_courses', function (Blueprint $table) {
//            $table->increments('id');
            $table->integer('semester_id')->unsigned();
            $table->integer('subject_id')->unsigned();
            $table->integer('admin_id')->unsigned();
            $table->integer('doctor_id')->unsigned();

            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');

            $table->primary(['semester_id','subject_id','admin_id','doctor_id'],'id');

            $table->integer('num_dr');
            $table->integer('num_ta');

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
        Schema::dropIfExists('open_courses');
    }
}

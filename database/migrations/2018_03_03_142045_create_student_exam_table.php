<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_student', function (Blueprint $table) {
            $table->integer('student_id')->unsigned();
            $table->integer('exam_timetable_id')->unsigned();
            $table->integer('sa_id')->unsigned()->comment('Student affairs id');

            $table->foreign('sa_id')->references('id')->on('student_affairs')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('exam_timetable_id')->references('id')->on('subjects')->onDelete('cascade');
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
        Schema::dropIfExists('exam_student');
    }
}

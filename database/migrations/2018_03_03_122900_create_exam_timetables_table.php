<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamTimetablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_timetables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('semester_id')->unsigned();
            $table->integer('subject_id')->unsigned();
            $table->integer('place_id')->unsigned();
            $table->integer('sa_id')->unsigned()->comment('Student affairs id');
            $table->integer('ta_id')->unsigned()->comment('Teacher assistant id');

            $table->foreign('ta_id')->references('id')->on('teacher_assistants')->onDelete('cascade');
            $table->foreign('sa_id')->references('id')->on('student_affairs')->onDelete('cascade');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
            $table->foreign('subject_id')->references('subject_id')->on('open_courses')->onDelete('cascade');
            $table->foreign('place_id')->references('id')->on('places')->onDelete('cascade');

            $table->integer('duration');
            $table->string('day');
            $table->time('time');
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
        Schema::dropIfExists('exam_timetables');
    }
}

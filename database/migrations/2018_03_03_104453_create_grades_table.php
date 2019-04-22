<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->integer('student_id')->unsigned();
            $table->integer('semester_id')->unsigned();
            $table->integer('subject_id')->unsigned();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');

            $table->primary(['student_id','semester_id','subject_id'],'id');

            $table->float('section');
            $table->float('quiz');
            $table->float('final');
            $table->float('midterm');
            $table->float('participation');
            $table->float('attendance');
            $table->float('project');
            $table->float('assignment');

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
        Schema::dropIfExists('grades');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradeDistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_distributions', function (Blueprint $table) {
            $table->integer('semester_id')->unsigned();
            $table->integer('subject_id')->unsigned();
            $table->integer('doctor_id')->unsigned();

            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('cascade');

            $table->primary(['semester_id','subject_id','doctor_id'],'id');
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
        Schema::dropIfExists('grade_distributions');
    }
}

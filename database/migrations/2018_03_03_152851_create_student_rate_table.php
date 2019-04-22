<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('student_rate')) {
            Schema::create('student_rate', function (Blueprint $table) {
                $table->integer('student_id')->unsigned();
                $table->integer('subject_id')->unsigned();
                $table->integer('semester_id')->unsigned();

                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
                $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
                $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');

                $table->primary(['semester_id', 'subject_id', 'semester_id'], 'id');

                $table->integer('rate');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_rate');
    }
}

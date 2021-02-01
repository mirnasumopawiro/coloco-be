<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employment_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee_id');
            $table->integer('job_id')->unsigned();
            $table->integer('department_id')->unsigned();
            $table->string('type');
            $table->dateTime('join_date');
            $table->dateTime('end_date')->nullable();
            $table->foreign('employee_id')
                  ->references('id')->on('employees')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('job_id')
                  ->references('id')->on('jobs')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');     
            $table->foreign('department_id')
                  ->references('id')->on('departments')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employment_details');
    }
}

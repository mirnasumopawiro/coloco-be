<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeePaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_payment_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee_id');
            $table->string('account_name');
            $table->string('account_no');
            $table->string('npwp');
            $table->string('bpjs_kesehatan_no');
            $table->string('bpjs_ketenagakerjaan_no');
            $table->foreign('employee_id')
                  ->references('id')->on('employees')
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
        Schema::dropIfExists('employee_payment_details');
    }
}

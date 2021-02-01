<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->string('employee_id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('phone_number');
            $table->string('profile_picture_url')->nullable();
            $table->string('place_of_birth');
            $table->dateTime('date_of_birth');
            $table->string('gender');
            $table->string('marital_status');
            $table->string('religion');
            $table->string('current_address');
            $table->string('identity_type');
            $table->string('identity_number');
            $table->dateTime('identity_exp_date')->nullable();
            $table->string('identity_address');
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
        Schema::dropIfExists('employee_profile');
    }
}

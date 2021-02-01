<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('issuer_id');
            $table->integer('department_id')->unsigned()->nullable();
            $table->string('resolver_id')->nullable();
            $table->string('title');
            $table->integer('status');
            $table->integer('type');
            $table->integer('urgency');
            $table->longText('notes');
            $table->dateTime('date_created');
            $table->foreign('issuer_id')
                  ->references('id')->on('employees')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->foreign('resolver_id')
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
        Schema::dropIfExists('tickets');
    }
}

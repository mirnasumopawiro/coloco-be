<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReimbursementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reimbursement', function (Blueprint $table) {
            $table->increments('id');
            $table->string('issuer_id');
            $table->string('resolver_id')->nullable();
            $table->dateTime('transaction_date');
            $table->integer('status');
            $table->integer('type');
            $table->longText('notes')->nullable();
            $table->string('proof_file_url')->nullable();
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
        Schema::dropIfExists('reimbursement');
    }
}

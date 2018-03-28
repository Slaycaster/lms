<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanPaymentTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payment_terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('loan_payment_term_name');
            $table->unsignedInteger('loan_payment_term_no_of_months');
            $table->unsignedInteger('loan_payment_term_collection_cycle');
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
        Schema::dropIfExists('loan_payment_terms');
    }
}

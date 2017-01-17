<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->double('loan_application_amount');
            $table->text('loan_application_purpose');
            $table->string('loan_application_status');
            $table->double('loan_application_filing_fee');
            $table->double('loan_application_service_fee');
            $table->text('loan_application_remarks');
            //RELATIONSHIPS
            $table->integer('loan_application_comaker_id1'); //(borrower_id)
            $table->integer('loan_application_comaker_id2'); //(borrower_id)
            $table->integer('loan_borrower_id');
            $table->integer('payment_term_id');
            $table->integer('loan_interest_id');
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
        Schema::dropIfExists('loan_applications');
    }
}

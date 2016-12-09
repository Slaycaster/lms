<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBorrowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrowers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('borrower_type');
            $table->string('borrower_last_name');
            $table->string('borrower_first_name');
            $table->string('borrower_middle_name')->nullable();
            $table->text('borrower_home_address');
            $table->string('borrower_email');
            $table->string('borrower_civil_status');
            $table->date('borrower_birth_date');
            $table->date('borrower_employment_date');
            $table->date('borrower_assignment_date');
            $table->string('borrower_spouse_name')->nullable();
            $table->unsignedInteger('borrower_no_of_children')->nullable();
            $table->integer('company_id');
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
        Schema::dropIfExists('borrowers');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/
	protected $table = 'loan_applications';
	protected $primaryKey = 'id';
	// protected $guarded = [];
	protected $hidden = ['id', 'created_at', 'updated_at'];
	protected $fillable = ['loan_application_amount', 'loan_application_purpose', 'loan_application_status', 'loan_application_comaker_name1', 'loan_application_comaker_name2', 'loan_borrower_id', 'payment_term_id', 'loan_interest_id'];
	public $timestamps = true;

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/

	public function loan_borrower()
	{
		return $this->belongsTo('App\Borrower', 'loan_borrower_id', 'id');
	}

	public function loan_interest()
	{
		return $this->belongsTo('App\LoanInterest', 'loan_interest_id', 'id');
	}

	public function loan_payment_term()
	{
		return $this->belongsTo('App\LoanPaymentTerm', 'loan_payment_term', 'id');
	}

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| ACCESORS
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| MUTATORS
	|--------------------------------------------------------------------------
	*/
}

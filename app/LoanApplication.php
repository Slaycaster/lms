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
	//protected $hidden = ['created_at', 'updated_at'];
	protected $fillable = ['loan_application_is_active', 'loan_application_amount', 'loan_application_total_amount', 'loan_application_interest', 'loan_application_periodic_rate', 'loan_application_purpose', 'loan_application_status', 'loan_application_filing_fee', 'loan_application_service_fee', 'loan_application_remarks', 'loan_application_disbursement_date', 'loan_application_comaker_id1', 'loan_application_comaker_id2', 'loan_borrower_id', 'payment_term_id', 'loan_interest_id', 'payment_schedule_id', 'created_at'];
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
		return $this->belongsTo('App\LoanPaymentTerm', 'payment_term_id', 'id');
	}

	public function comaker1()
	{
		return $this->belongsTo('App\Borrower', 'loan_application_comaker_id1', 'id');
	}

	public function comaker2()
	{
		return $this->belongsTo('App\Borrower', 'loan_application_comaker_id2', 'id');
	}

	public function payment_schedule()
	{
		return $this->belongsTo('App\PaymentSchedule', 'payment_schedule_id', 'id');
	}

	public function loan_payments()
	{
		return $this->hasMany('App\LoanPayment', 'loan_application_id');
	}

	public function payment_collections()
	{
		return $this->hasMany('App\PaymentCollection', 'loan_application_id');
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

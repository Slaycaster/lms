<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentCollection extends Model
{
     /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/

	protected $table = 'payment_collections';
	protected $primaryKey = 'id';
	// protected $guarded = [];
	protected $hidden = ['id', 'created_at', 'updated_at'];
	protected $fillable = ['payment_collection_date',  'payment_collection_amount', 'loan_application_id', 'is_paid'];
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

	public function loan_application()
	{
		return $this->belongsTo('App\LoanApplication', 'loan_application_id', 'id');
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
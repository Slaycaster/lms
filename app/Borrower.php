<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Borrower extends Model
{
    use CrudTrait;

    /*
	|--------------------------------------------------------------------------
	| GLOBAL VARIABLES
	|--------------------------------------------------------------------------
	*/
	protected $table = 'borrowers';
	protected $primaryKey = 'id';
	// protected $guarded = [];
	protected $hidden = ['created_at', 'updated_at'];
	protected $fillable = ['borrower_type', 'borrower_status', 'borrower_last_name', 'borrower_first_name', 'borrower_middle_name', 'borrower_home_address', 'borrower_email', 'borrower_civil_status', 'borrower_birth_date', 'borrower_employment_date', 'borrower_assignment_date', 'borrower_salary_gross_pay', 'borrower_monthly_expenses', 'borrower_resignation_date', 'borrower_spouse_name', 'borrower_no_of_children', 'company_id'];
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

	public function company()
	{
		return $this->belongsTo('App\Company', 'company_id', 'id');
	}

	public function loan_applications()
	{
		return $this->hasMany('App\LoanApplication', 'borrower_id');
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

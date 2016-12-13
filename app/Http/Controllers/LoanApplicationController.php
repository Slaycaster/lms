<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\LoanApplication;
use App\LoanPaymentTerm;
use App\LoanInterest;
use App\Borrower;
use Yajra\Datatables\Datatables;

class LoanApplicationController extends Controller
{
    public function index()
    {
    	$payment_terms = LoanPaymentTerm::pluck('loan_payment_term_name', 'id');
    	$loan_interests = LoanInterest::pluck('loan_interest_name', 'id');
    	$loan_applications = LoanApplication::all();
    	return view('loan_application')
    		->with('loan_applications', $loan_applications)
    		->with('payment_terms', $payment_terms)
    		->with('loan_interests', $loan_interests);
    }

    public function borrowers()
    {
    	$borrowers = Borrower::select(['id', 'borrower_type', 'borrower_last_name', 'borrower_first_name', 'borrower_middle_name', 'borrower_employment_date', 'borrower_assignment_date']);
    	return Datatables::of($borrowers)
    		->add_column('Actions', '{{Form::radio(\'borrower_id\', $id, false)}}')
    		->remove_column('id')
    		->make();
    }
}

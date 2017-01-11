<?php

namespace App\Http\Controllers;

use Session, DB, Validator, Input, Redirect, Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\LoanApplication;
use App\LoanPaymentTerm;
use App\LoanInterest;
use App\Borrower;
use Yajra\Datatables\Datatables;


class LoanPaymentController extends Controller
{
    /*==============================================================
                        Laravel Views
	==============================================================*/
	public function index()
	{
		return view('loan-payment.index');
	}

    public function payment_view($id)
    {
        $loan_application = LoanApplication::where('id', '=', $id)
            ->with('loan_borrower')
            ->with('loan_borrower.company')
            ->with('loan_interest')
            ->with('loan_payment_term')
            ->with('loan_payments')
            ->get();
        return view('loan-payment.payment')
            ->with('loan_application', compact('loan_application'));
    }

	/*==============================================================
                        AJAX-loaded data
	==============================================================*/

	public function approved_data()
    {
        $loan_applications = LoanApplication::where('loan_application_status', '=', 'Approved')
            ->with('loan_borrower')
            ->with('loan_interest')
            ->with('loan_payment_term')
            ->with('loan_borrower.company')
            ->with('loan_payments')
            ->select('loan_applications.*');

        return Datatables::of($loan_applications)
            ->add_column('Actions', '<a href=\'{{ url(\'admin/loan_payments/\' . $id )}}\' class=\'btn btn-primary btn-xs\'> Details </a>')
            ->add_column('loan_payments', function($loan_application) {
            	return $loan_application->loan_payments->sum('loan_payment_amount');
            })
            ->remove_column('id')
            ->make();
    }
}

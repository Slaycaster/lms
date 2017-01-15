<?php

namespace App\Http\Controllers;

use Session, DB, Validator, Input, Redirect, Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\LoanApplication;
use App\LoanPaymentTerm;
use App\LoanInterest;
use App\Borrower;
use App\LoanPayment;
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
                    Eloquent backend scripts
    ==============================================================*/

    public function process_payment()
    {
        $loan_payment = new LoanPayment();
        $loan_payment->loan_application_id = Request::input('loan_application_id');
        $loan_payment->loan_payment_amount = Request::input('amount');
        $loan_payment->remarks = Request::input('remarks');
        $loan_payment->save();

        Session::flash('message', 'Loan Payment Successful!');

        return Redirect::to('admin/loan_payments');
    }

    public function process_due_payment()
    {
        $monthsUnpaid = Request::input('months_unpaid');

        $loan_payment = new LoanPayment();
        $loan_payment->loan_application_id = Request::input('loan_application_id');
        $loan_payment->loan_payment_amount = Request::input('due_amount');
        $loan_payment->remarks = Request::input('due_remarks') . ' (Delayed payment for about ' . $monthsUnpaid . ' months. ';
        $loan_payment->save();

        Session::flash('message', 'Loan Payment Successful!');

        return Redirect::to('admin/loan_payments');
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

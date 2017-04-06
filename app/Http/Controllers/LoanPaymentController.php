<?php

namespace App\Http\Controllers;

use Session, DB, Validator, Input, Redirect, Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

//Model 
use App\LoanApplication;
use App\LoanPaymentTerm;
use App\LoanInterest;
use App\Borrower;
//use App\LoanPayment;
//NEW
use App\PaymentCollection;

//Third-party
use Yajra\Datatables\Datatables;
use Barryvdh\DomPDF\Facade as PDF;

use App\User;
use Illuminate\Support\Facades\Auth;

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
            ->with('payment_collections')
            ->get();
        return view('loan-payment.payment')
            ->with('loan_application', compact('loan_application'));
    }

    /*==============================================================
                    Eloquent backend scripts
    ==============================================================*/

    public function process_payment()
    {
        $payment_collection_id = Request::input('payment_collection_id');
        for ($i = 0; $i < sizeof($payment_collection_id); $i++)
        {
            $payment_collection = PaymentCollection::find($payment_collection_id[$i]);
            $payment_collection->is_paid = 1;
            $payment_collection->save();
        }

        Session::flash('message', 'Loan Payment Successful!');

        return Redirect::to('admin/loan_payments');
    }

    public function process_due_payment()
    {

    }
/*
    public function process_payment()
    {
        $loan_payment = new LoanPayment();
        $loan_payment->loan_application_id = Request::input('loan_application_id');
        $loan_payment->loan_payment_amount = Request::input('amount');
        $loan_payment->loan_payment_count = 1;
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
        $loan_payment->loan_payment_count = 1 + $monthsUnpaid;
        $loan_payment->remarks = Request::input('due_remarks') . ' (Delayed payment for about ' . $monthsUnpaid . ' months. ';
        $loan_payment->save();

        Session::flash('message', 'Loan Payment Successful!');

        return Redirect::to('admin/loan_payments');
    }
*/
    /*==============================================================
                            DOMPDF Views
    ==============================================================*/

    public function promissory_note($id)
    {
        Session::put('application_id', $id);
        //Session::put('date', Request::input('date'));
        $pdf = PDF::loadView('reports.promissory-pdf')->setPaper('Folio');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(808, 580, "Moo Loans Inc. - Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream();
    }

	/*==============================================================
                        AJAX-loaded data
	==============================================================*/

	public function approved_data()
    {
        if (Auth::user()->company->id == 1)
        {
            $loan_applications = LoanApplication::where('loan_application_status', '=', 'Approved')
                ->with('loan_interest')
                ->with('loan_payment_term')
                ->with('loan_borrower.company')
                ->with('payment_collections')
                ->orderBy('id', 'desc');
            return Datatables::of($loan_applications)
                ->add_column('Actions', '<a href=\'{{ url(\'admin/loan_payments/\' . $id )}}\' class=\'btn btn-primary btn-xs\' target=\'_blank\'> Payment </a>')
                ->make();
        }
        else
        {
            $loan_applications = LoanApplication::where('loan_application_status', '=', 'Approved')
                ->with(['loan_borrower' => function($q) {
                    $q->where('company_id', '=', Auth::user()->company->id);
                }])
                ->with('loan_interest')
                ->with('loan_payment_term')
                ->with('loan_borrower.company')
                ->with('payment_collections')
                ->orderBy('id', 'desc');
                return Datatables::of($loan_applications)
                    ->add_column('Actions', '<a href=\'{{ url(\'admin/loan_payments/\' . $id )}}\' class=\'btn btn-primary btn-xs\' target=\'_blank\'> Payment </a>')
                    ->make();
        }
        return json_encode($loan_applications, JSON_PRETTY_PRINT);
    }
}

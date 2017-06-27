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
        if (Request::input('payment_collection_id') == null)
        {
            Session::flash('payment-error', 'Error! Kindly check the collection date specified!');

            return Redirect::to('admin/loan_payments/'.Request::input('application_id'));
        }
        else
        {
            for ($i = 0; $i < sizeof($payment_collection_id); $i++)
            {
                $payment_collection = PaymentCollection::find($payment_collection_id[$i]);
                $payment_collection->is_paid = 1;
                $payment_collection->save();
            }

            $unpaid_count = PaymentCollection::where('loan_application_id', '=', Request::input('application_id'))->where('is_paid', '=', 0)->count();

            if ($unpaid_count != 0)
            {
                Session::flash('payment_status', 'There are '. $unpaid_count .' payment collections remaining.');            
            }
            else
            {
                Session::flash('payment_status', 'It seems all payment collections have been paid, loan cleared!');

                //Update the loan application to cleared (Archive it)
                $loan_application = LoanApplication::find(Request::input('application_id'));
                $loan_application->loan_application_is_active = 0;
                $loan_application->loan_application_status = 'Cleared';
                $loan_application->save();
            }

            Session::flash('message', 'Loan Payment Successful!');

            return Redirect::to('admin/loan_payments');
        }
    }

    public function process_termination()
    {
        $loan_application_id = Request::input('application_id');
        $next_due_date = Request::input('next_due_date');
        $termination_fee = Request::input('termination_fee');
        $interest_termination = Request::input('interest_termination');

        //Update the loan application to terminated (Archive it)
        $loan_application = LoanApplication::find(Request::input('application_id'));
        $loan_application->loan_application_is_active = 0;
        $loan_application->loan_application_status = 'Terminated';
        $loan_application->save();

        //Update the next due date's amount to be in full, terminated amount
        DB::table('payment_collections')
            ->where('payment_collection_date', '=', $next_due_date)
            ->where('loan_application_id', '=', $loan_application_id)
            ->update(array('payment_collection_principal_amount' => $termination_fee, 'payment_collection_interest_amount' => $interest_termination, 'is_paid' => 1));

        //Updates the rest of the payment collection's amount to 0.
        DB::table('payment_collections')
            ->where('loan_application_id', '=', $loan_application_id)
            ->where('is_paid', '=', 0)
            ->update(array('payment_collection_principal_amount' => 0, 'payment_collection_interest_amount' => 0, 'is_paid' => 1));

        Session::flash('message', 'Loan Termination Successful!');

        return Redirect::to('admin/loan_payments');
    }

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
                ->select('loan_applications.*');
            return Datatables::of($loan_applications)
                ->add_column('Actions', '<a href=\'{{ url(\'admin/loan_payments/\' . $id )}}\' class=\'btn btn-primary btn-xs\' > Payment </a>')
                ->make(true);
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
                ->select('loan_applications.*');
                return Datatables::of($loan_applications)
                    ->add_column('Actions', '<a href=\'{{ url(\'admin/loan_payments/\' . $id )}}\' class=\'btn btn-primary btn-xs\' > Payment </a>')
                    ->make(true);
        }
        return json_encode($loan_applications, JSON_PRETTY_PRINT);
    }
}

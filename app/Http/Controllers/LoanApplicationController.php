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

class LoanApplicationController extends Controller
{

/*==============================================================
                        Laravel Views
==============================================================*/

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

    public function pending_view()
    {
        return view('loan_application_pending');
    }

    public function declined_view()
    {
        return view('loan_application_declined');
    }

    public function details($id)
    {
        $loan_application = LoanApplication::where('id', '=', $id)
            ->with('loan_borrower')
            ->with('loan_borrower.company')
            ->with('loan_interest')
            ->with('loan_payment_term')
            ->get();
        //dd($loan_application);
        return view('loan_application_details')
            ->with('loan_application', compact('loan_application'));
    }

/*==============================================================
                    DOMPDF Views
==============================================================*/

    public function promissory_note($id)
    {
        Session::put('application_id', $id);
        //Session::put('date', Request::input('date'));
        $pdf = PDF::loadView('reports.promissory-pdf')->setPaper('Letter');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(808, 580, "Moo Loans Inc. - Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream();
    }

/*==============================================================
                    Eloquent Backend Scripts
==============================================================*/

    public function save()
    {
        /*--------------------------------------------------
            Save the current transaction to the database
        ---------------------------------------------------*/
        $loan_application = new LoanApplication();
        $loan_application->loan_application_amount = Request::input('amount');
        $loan_application->loan_application_purpose = Request::input('purpose');
        $loan_application->loan_application_status = "Pending";
        $loan_application->loan_application_filing_fee = Request::input('filing_fee');
        $loan_application->loan_application_service_fee = Request::input('service_fee');
        //Relationships
        $loan_application->loan_application_comaker_id1 = Request::input('comaker1_id');
        $loan_application->loan_application_comaker_id2 = Request::input('comaker2_id');
        $loan_application->loan_borrower_id = Request::input('borrower_id');
        $loan_application->payment_term_id = Request::input('payment_term_id');
        $loan_application->loan_interest_id = Request::input('loan_interest_id');
        $loan_application->save();

        Session::flash('message', 'Loan Application successfully saved! It is now currently pending for approval.');

        return Redirect::to('admin/loan_applications');
    }

    public function process_application()
    {
        /*--------------------------------------------------
            Update the current loan application to the database
        ---------------------------------------------------*/
        $loan_application = LoanApplication::find(Request::input('loan_application_id'));
        $loan_application->loan_application_amount = Request::input('amount');
        if (isset($_POST['approve']))
        {
            $loan_application->loan_application_status = "Approved";
            Session::flash('message', 'Loan Application Approved!');
        } 
        else if (isset($_POST['decline']))
        {
            $loan_application->loan_application_status = "Declined";
            Session::flash('message', 'Loan Application Declined!');
        }
        $loan_application->loan_application_remarks = Request::input('remarks');
        $loan_application->save();


        return Redirect::to('admin/loan_applications');
    }


/*==============================================================
                        AJAX-loaded data
==============================================================*/

    public function borrowers()
    {
    	$borrowers = Borrower::select(['id', 'borrower_type', 'borrower_last_name', 'borrower_first_name', 'borrower_middle_name', 'borrower_employment_date', 'borrower_assignment_date']);
    	return Datatables::of($borrowers)
    		->add_column('Actions', '{{Form::radio(\'borrower_id\', $id, false)}}')
    		->remove_column('id')
    		->make();
    }

    public function comaker1()
    {
        $borrowers = Borrower::select(['id', 'borrower_type', 'borrower_last_name', 'borrower_first_name', 'borrower_middle_name', 'borrower_employment_date', 'borrower_assignment_date']);
        return Datatables::of($borrowers)
            ->add_column('Actions', '{{Form::radio(\'comaker1_id\', $id, false)}}')
            ->remove_column('id')
            ->make();
    }

    public function comaker2()
    {
        $borrowers = Borrower::select(['id', 'borrower_type', 'borrower_last_name', 'borrower_first_name', 'borrower_middle_name', 'borrower_employment_date', 'borrower_assignment_date']);
        return Datatables::of($borrowers)
            ->add_column('Actions', '{{Form::radio(\'comaker2_id\', $id, false)}}')
            ->remove_column('id')
            ->make();
    }

    public function pending_data()
    {
        $loan_applications = LoanApplication::where('loan_application_status', '=', 'Pending')
            ->with('loan_borrower')
            ->with('loan_interest')
            ->with('loan_payment_term')
            ->with('loan_borrower.company')
            ->select('loan_applications.*');
        return Datatables::of($loan_applications)
            ->add_column('Actions', '<a href=\'{{ url(\'admin/loan_applications/details/\' . $id )}}\' class=\'btn btn-primary btn-xs\'> Details </a>')
            ->remove_column('id')
            ->make();
    }

    public function declined_data()
    {
        $loan_applications = LoanApplication::where('loan_application_status', '=', 'Declined')
            ->with('loan_borrower')
            ->with('loan_interest')
            ->with('loan_payment_term')
            ->with('loan_borrower.company')
            ->select('loan_applications.*');
        return Datatables::of($loan_applications)
            ->add_column('Actions', '<a href=\'{{ url(\'admin/loan_applications/details/\' . $id )}}\' class=\'btn btn-primary btn-xs\'> Details </a>')
            ->remove_column('id')
            ->make();
    }

}

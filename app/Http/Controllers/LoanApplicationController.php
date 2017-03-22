<?php

namespace App\Http\Controllers;

use Session, DB, Validator, Input, Redirect, Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\LoanApplication;
use App\LoanPaymentTerm;
use App\PaymentSchedule;
use App\PaymentCollection;
use App\LoanInterest;
use App\Borrower;
use Yajra\Datatables\Datatables;

use App\User;
use Illuminate\Support\Facades\Auth;

//PHP
use DateTime, DateInterval, DatePeriod;

class LoanApplicationController extends Controller
{
/*==============================================================
                        Laravel Views
==============================================================*/

    public function index()
    {
        return view('loan_application_index');
    }

    public function active()
    {
        return view('loan_application_active');
    }

    public function create()
    {
        $payment_terms = LoanPaymentTerm::pluck('loan_payment_term_name', 'id');
        $loan_interests = LoanInterest::pluck('loan_interest_name', 'id');
        $payment_schedules = PaymentSchedule::pluck('payment_schedule_name', 'id');
        $loan_applications = LoanApplication::all();
        return view('loan_application')
            ->with('loan_applications', $loan_applications)
            ->with('payment_terms', $payment_terms)
            ->with('payment_schedules', $payment_schedules)
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
            ->with('payment_collections')
            ->get();

        return view('loan_application_details')
            ->with('loan_application', compact('loan_application'));
    }


/*==============================================================
                    Eloquent Backend Scripts
==============================================================*/

    public function save(Request $request)
    {
        /*
        $rules = [
            'loan_application_amount' => 'required|numeric',
            'filing_fee' => 'required|numeric',
            'service_fee' => 'required|numeric',
            'disbursement_date' => 'required',
            'purpose' => 'required',
            'borrower_id' => 'required',
            'comaker1_id' => 'required|not_in:'.Request::input('borrower_id'),
            'comaker2_id' => 'required|not_in:'.Request::input('borrower_id')
        ];

        $messages = [
             'required' => 'The :attribute field is required.',
             'not_in' => 'The Co-Maker and the Client should not be the same!'
        ];
        $this->validate($request, $rules, $messages);

        //$validator = Validator::make($request->all(), $rules, $messages);
        */
        //Compute
        //Get all post inputs
        $loan_application_amount = Request::input('amount');
        $filing_fee = Request::input('filing_fee');
        $service_fee = Request::input('service_fee');
        $disbursement_date = Request::input('disbursement_date');
        $payment_term_id = Request::input('payment_term_id');
        $payment_schedule_id = Request::input('payment_schedule_id');
        $interest_id = Request::input('loan_interest_id');

        //And query those data with ids to get the real meat out of it.
        $payment_term = LoanPaymentTerm::where('id', '=', $payment_term_id)->first();
        $payment_schedule = PaymentSchedule::where('id', '=', $payment_schedule_id)->first();
        $interest = LoanInterest::where('id', '=', $interest_id)->first();

        $monthlyInterest = $loan_application_amount * ($interest->loan_interest_rate * .01);

        $totalLoan = $loan_application_amount +  $filing_fee + $service_fee + ($monthlyInterest * $payment_term->loan_payment_term_no_of_months);

        //Starting Month and Year for your payment since the loan was disbursed
        $paymentStartDate = (new DateTime(date('Y-m-d', strtotime($disbursement_date .'+'. $payment_schedule->payment_schedule_days_interval . ' days'))));

        //Ending Month and year for your payment since the loan was disbursed
        $paymentEndDate = (new DateTime(date('Y-m-d', strtotime($disbursement_date .'+'. $payment_term->loan_payment_term_no_of_months . 'months' .'+'. $payment_schedule->payment_schedule_days_interval . ' days'))));

        //payment interval based on given payment schedule
        $paymentInterval = DateInterval::createFromDateString($payment_schedule->payment_schedule_days_interval . ' days');

        //*IMPORTANT* Compute the payment schedules from start to finish
        $paymentPeriod = new DatePeriod($paymentStartDate, $paymentInterval, $paymentEndDate);

        $paymentPeriod_count = 0;

        //Declare an empty array that'll place the computed payment periods
        $payment_periods = array();

        //Loop through each payment period to count for the total payment
        foreach ($paymentPeriod as $dt) {
            $payment_periods[] = $dt->format('M j, Y');
            $paymentPeriod_count++;
        }

        $periodicRate = $totalLoan / $paymentPeriod_count;

        /*--------------------------------------------------
            Save the loan application to the database
        ---------------------------------------------------*/
        $loan_application = new LoanApplication();
        $loan_application->loan_application_is_active = 1;
        $loan_application->loan_application_amount = Request::input('amount');
        $loan_application->loan_application_total_amount = round($totalLoan, 2);
        $loan_application->loan_application_interest = round($monthlyInterest, 2);
        $loan_application->loan_application_periodic_rate = round($periodicRate, 2);
        $loan_application->loan_application_purpose = Request::input('purpose');
        $loan_application->loan_application_status = "Pending";
        $loan_application->loan_application_filing_fee = Request::input('filing_fee');
        $loan_application->loan_application_service_fee = Request::input('service_fee');
        $loan_application->loan_application_disbursement_date = Request::input('disbursement_date');
        //Relationships
        $loan_application->loan_application_comaker_id1 = Request::input('comaker1_id');
        $loan_application->loan_application_comaker_id2 = Request::input('comaker2_id');
        $loan_application->loan_borrower_id = Request::input('borrower_id');
        $loan_application->payment_term_id = Request::input('payment_term_id');
        $loan_application->loan_interest_id = Request::input('loan_interest_id');
        $loan_application->payment_schedule_id = Request::input('payment_schedule_id');
        $loan_application->save();

        //Query the id of the recently saved Loan Application
        $loan_application_max = LoanApplication::max('id');

        //Loop through each payment period and place it on to the array for the JSON
        foreach ($paymentPeriod as $dt) {
            $payment_collection = new PaymentCollection();
            $payment_collection->is_paid = 0;
            $payment_collection->payment_collection_date = $dt->format('Y-m-d');
            $payment_collection->payment_collection_amount = round($periodicRate, 2);
            $payment_collection->loan_application_id = $loan_application_max;

            $payment_collection->save();
        }


        Session::flash('message', 'Loan Application successfully saved! It is now currently pending for approval.');

        return Redirect::to('admin/loan_applications');
    }

    public function process_application()
    {
        /*--------------------------------------------------
            Update the current loan application to the database
        ---------------------------------------------------*/
        $loan_application = LoanApplication::find(Request::input('loan_application_id'));
        
        if (Request::input('amount'))
        {
            $loan_application->loan_application_amount = Request::input('amount');
        }
        if (Request::input('disbursement_date'))
        {
            $loan_application->loan_application_disbursement_date = Request::input('disbursement_date');
        }
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

/*
    public function borrowers()
    {
        if (Auth::user()->company->id == 1)
        {
        	$borrowers = Borrower::select(['id', 'borrower_type', 'borrower_last_name', 'borrower_first_name', 'borrower_middle_name', 'borrower_employment_date', 'borrower_assignment_date']);
        	return Datatables::of($borrowers)
        		->add_column('Actions', '{{Form::radio(\'borrower_id\', $id, false)}}')
        		->remove_column('id')
        		->make();
        }
        else
        {
            $borrowers = Borrower::select(['id', 'borrower_type', 'borrower_last_name', 'borrower_first_name', 'borrower_middle_name', 'borrower_employment_date', 'borrower_assignment_date'])
                ->where('company_id', '=', Auth::user()->company->id);
            return Datatables::of($borrowers)
                ->add_column('Actions', '{{Form::radio(\'borrower_id\', $id, false)}}')
                ->remove_column('id')
                ->make();
        }
    }
    public function comaker1()
    {
        if (Auth::user()->company->id == 1)
        {
            $borrowers = Borrower::select(['id', 'borrower_type', 'borrower_last_name', 'borrower_first_name', 'borrower_middle_name', 'borrower_employment_date', 'borrower_assignment_date']);
            return Datatables::of($borrowers)
                ->add_column('Actions', '{{Form::radio(\'comaker1_id\', $id, false)}}')
                ->remove_column('id')
                ->make();
        }
        else
        {
            $borrowers = Borrower::select(['id', 'borrower_type', 'borrower_last_name', 'borrower_first_name', 'borrower_middle_name', 'borrower_employment_date', 'borrower_assignment_date'])
                ->where('company_id', '=', Auth::user()->company->id);
            return Datatables::of($borrowers)
                ->add_column('Actions', '{{Form::radio(\'comaker1_id\', $id, false)}}')
                ->remove_column('id')
                ->make();
        }
    }

    public function comaker2()
    {
        if (Auth::user()->company->id == 1)
        {
            $borrowers = Borrower::select(['id', 'borrower_type', 'borrower_last_name', 'borrower_first_name', 'borrower_middle_name', 'borrower_employment_date', 'borrower_assignment_date']);
            return Datatables::of($borrowers)
                ->add_column('Actions', '{{Form::radio(\'comaker2_id\', $id, false)}}')
                ->remove_column('id')
                ->make();
        }
        else
        {
            $borrowers = Borrower::select(['id', 'borrower_type', 'borrower_last_name', 'borrower_first_name', 'borrower_middle_name', 'borrower_employment_date', 'borrower_assignment_date'])
                ->where('company_id', '=', Auth::user()->company->id);
            return Datatables::of($borrowers)
                ->add_column('Actions', '{{Form::radio(\'comaker2_id\', $id, false)}}')
                ->remove_column('id')
                ->make();
        }
    }
*/

    public function borrowers()
    {
        if (Auth::user()->company->id == 1)
        {
            $param1 = Request::input('para1');
            $borrowers = Borrower::join('companies', 'borrowers.company_id', '=', 'companies.id')
                ->where('borrower_last_name', 'LIKE', '%' . $param1 . '%')
                ->get(['borrowers.id', 'borrower_last_name', 'borrower_first_name', 'borrower_middle_name', 'companies.company_code']);
            return json_encode($borrowers, JSON_PRETTY_PRINT);
        }
        else
        {
            $param1 = Request::input('para1');
            $borrowers = Borrower::join('companies', 'borrowers.company_id', '=', 'companies.id')
                ->where('borrower_last_name', 'LIKE', '%' . $param1 . '%')
                ->where('company_id', '=', Auth::user()->company->id)
                ->get(['borrowers.id', 'borrower_last_name', 'borrower_first_name', 'borrower_middle_name', 'companies.company_code']);
            return json_encode($borrowers, JSON_PRETTY_PRINT);
        }
    }

    public function index_data()
    {
        if (Auth::user()->company->id == 1)
        {
            $loan_applications = LoanApplication::where('loan_application_is_active', '=', '1')
                ->with('loan_borrower')
                ->with('loan_interest')
                ->with('loan_payment_term')
                ->with('loan_borrower.company')
                ->select('loan_applications.*')
                ->orderBy('id', 'desc');
            return Datatables::of($loan_applications)
                ->add_column('Actions', '<a href=\'{{ url(\'admin/loan_applications/\' . $id )}}\' class=\'btn btn-primary btn-xs\'> Details </a>')
                ->make();
        }
        else
        {
            $loan_applications = LoanApplication::where('loan_application_is_active', '=', '1')
                ->with(['loan_borrower' => function($q) {
                    $q->where('company_id', '=', Auth::user()->company->id);
                }])
                ->with('loan_interest')
                ->with('loan_payment_term')
                ->with('loan_borrower.company')
                ->select('loan_applications.*')
                ->orderBy('id', 'desc');
            return Datatables::of($loan_applications)
                ->add_column('Actions', '<a href=\'{{ url(\'admin/loan_applications/\' . $id )}}\' class=\'btn btn-primary btn-xs\'> Details </a>')
                ->make();   
        }
    }

    public function active_data()
    {
        if (Auth::user()->company->id == 1)
        {
            $loan_applications = LoanApplication::where('loan_application_is_active', '=', '1')
                ->where('loan_application_status', '=', 'Pending')
                ->orWhere('loan_application_status', '=', 'Declined')
                ->with('loan_borrower')
                ->with('loan_interest')
                ->with('loan_payment_term')
                ->with('loan_borrower.company')
                ->select('loan_applications.*')
                ->orderBy('id', 'desc');
            return Datatables::of($loan_applications)
                ->add_column('Actions', '<a href=\'{{ url(\'admin/loan_applications/\' . $id )}}\' class=\'btn btn-primary btn-xs\'> Details </a>')
                ->make(); 
        }
        else
        {
            $loan_applications = LoanApplication::where('loan_application_is_active', '=', '1')
                ->with(['loan_borrower' => function($q) {
                    $q->where('company_id', '=', Auth::user()->company->id);
                }])
                ->where('loan_application_status', '=', 'Pending')
                ->orWhere('loan_application_status', '=', 'Declined')
                ->with('loan_interest')
                ->with('loan_payment_term')
                ->with('loan_borrower.company')
                ->select('loan_applications.*')
                ->orderBy('id', 'desc');
            return Datatables::of($loan_applications)
                ->add_column('Actions', '<a href=\'{{ url(\'admin/loan_applications/\' . $id )}}\' class=\'btn btn-primary btn-xs\'> Details </a>')
                ->make(); 
        }  
    }

    public function precompute()
    {
        //Get all post inputs
        $loan_application_amount = Request::input('loan_application_amount');
        $filing_fee = Request::input('filing_fee');
        $service_fee = Request::input('service_fee');
        $disbursement_date = Request::input('disbursement_date');
        $payment_term_id = Request::input('payment_term_id');
        $payment_schedule_id = Request::input('payment_schedule_id');
        $interest_id = Request::input('interest_id');

        //And query those data with ids to get the real meat out of it.
        $payment_term = LoanPaymentTerm::where('id', '=', $payment_term_id)->first();
        $payment_schedule = PaymentSchedule::where('id', '=', $payment_schedule_id)->first();
        $interest = LoanInterest::where('id', '=', $interest_id)->first();

        $monthlyInterest = $loan_application_amount * ($interest->loan_interest_rate * .01);

        $totalLoan = $loan_application_amount +  $filing_fee + $service_fee + ($monthlyInterest * $payment_term->loan_payment_term_no_of_months);

        //Starting Month and Year for your payment since the loan was disbursed
        $paymentStartDate = (new DateTime(date('Y-m-d', strtotime($disbursement_date .'+'. $payment_schedule->payment_schedule_days_interval . ' days'))));

        //Ending Month and year for your payment since the loan was disbursed
        $paymentEndDate = (new DateTime(date('Y-m-d', strtotime($disbursement_date .'+'. $payment_term->loan_payment_term_no_of_months . 'months' .'+'. $payment_schedule->payment_schedule_days_interval . ' days'))));

        //payment interval based on given payment schedule
        $paymentInterval = DateInterval::createFromDateString($payment_schedule->payment_schedule_days_interval . ' days');

        //*IMPORTANT* Compute the payment schedules from start to finish
        $paymentPeriod = new DatePeriod($paymentStartDate, $paymentInterval, $paymentEndDate);

        $paymentPeriod_count = 0;

        //Declare an empty array that'll place the computed payment periods
        $payment_periods = array();

        //Loop through each payment period and place it on to the array for the JSON
        foreach ($paymentPeriod as $dt) {
            $payment_periods[] = $dt->format('M j, Y');
            $paymentPeriod_count++;
        }

        $periodicRate = $totalLoan / $paymentPeriod_count;

        $data = array(
            "payment_periods" => $payment_periods,
            "total_loan" => round($totalLoan, 2),
            "monthly_interest" => round($monthlyInterest, 2),
            "payment_count" => $paymentPeriod_count,
            "periodic_rate" => round($periodicRate, 2)
            );

        return json_encode($data);
    }
}

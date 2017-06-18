<?php

namespace App\Http\Controllers;

use Session, DB, Validator, Input, Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

//Models
use App\LoanApplication;
use App\LoanPaymentTerm;
use App\PaymentSchedule;
use App\PaymentCollection;
use App\LoanInterest;
use App\Borrower;
use App\User;
    
//Third-party
use Yajra\Datatables\Datatables;
use Barryvdh\DomPDF\Facade as PDF;

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

    public function archives()
    {
        return view('loan_application_archives');
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
       $payment_terms = LoanPaymentTerm::pluck('loan_payment_term_name', 'id');
       $loan_interests = LoanInterest::pluck('loan_interest_name', 'id');
       $payment_schedules = PaymentSchedule::pluck('payment_schedule_name', 'id');
        $loan_application = LoanApplication::where('id', '=', $id)
            ->with('loan_borrower')
            ->with('loan_borrower.company')
            ->with('loan_interest')
            ->with('loan_payment_term')
            ->with('payment_collections')
            ->get();

        return view('loan_application_details')
            ->with('loan_application', $loan_application)
            ->with('payment_terms', $payment_terms)
            ->with('payment_schedules', $payment_schedules)
            ->with('loan_interests', $loan_interests);
    }

    public function viewdetails($id)
    {
       $payment_terms = LoanPaymentTerm::pluck('loan_payment_term_name', 'id');
       $loan_interests = LoanInterest::pluck('loan_interest_name', 'id');
       $payment_schedules = PaymentSchedule::pluck('payment_schedule_name', 'id');
        $loan_application = LoanApplication::where('id', '=', $id)
            ->with('loan_borrower')
            ->with('loan_borrower.company')
            ->with('loan_interest')
            ->with('loan_payment_term')
            ->with('payment_collections')
            ->get();
        $maturity_date = PaymentCollection::where('loan_application_id', '=', $id)
            ->orderBy('payment_collection_date', 'desc')
            ->first();

        return view('loan_application_view_details')
            ->with('loan_application', $loan_application)
            ->with('payment_terms', $payment_terms)
            ->with('payment_schedules', $payment_schedules)
            ->with('loan_interests', $loan_interests)
            ->with('maturity_date', $maturity_date);
    }

    /*==============================================================
                            DOMPDF Views
    ==============================================================*/

    public function promissory_note($id)
    {
        Session::put('application_id', $id);
        //Session::put('date', Request::input('date'));
        $pdf = PDF::loadView('reports.promissory-pdf')->setPaper('A4');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(808, 580, "Moo Loans Inc. - Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream();
    }

    public function payment_schedule($id)
    {
        Session::put('application_id', $id);
        $pdf = PDF::loadView('reports.payment_schedule-pdf')->setPaper('A4');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(808, 580, "Moo Loans Inc. - Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream();
    }

    public function generate_form($id)
    {
        Session::put('application_id', $id);
        $pdf = PDF::loadView('reports.loan_application-pdf')->setPaper('A4');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(808, 580, "Moo Loans Inc. - Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream();   
    }

    public function statement_of_account($id)
    {
        Session::put('application_id', $id);
        $pdf = PDF::loadView('reports.statement_of_account-pdf')->setPaper('A4');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(808, 580, "Moo Loans Inc. - Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return $pdf->stream();   
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
        $company = Borrower::where('id', '=', Request::input('borrower_id'))->first();
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

        $periodicRate = 0; //loan monthly/kinsenas/whatever rate
        $miscellaneousRate = 0; //if so happens that filing fee and service fee wouldn't be amortized
        $totalLoan = 0; //total loan (principal + interest)

        $monthlyInterest = $loan_application_amount * ($interest->loan_interest_rate * .01);

        
        $last_day_from_disbursement_date = date('Y-m-t', strtotime($disbursement_date));
        if (date('d', strtotime($disbursement_date)) < 15) //if the disbursement date was less than or equal to the 15th
        {
            $paymentStartDate = date('Y-m-15', strtotime($disbursement_date)); //Get the 15th day of the current month as start date
            $paymentEndDate = date('Y-m-15', strtotime($disbursement_date . '+' . ($payment_term->loan_payment_term_no_of_months) . 'months'));
        }
        else if (date('d', strtotime($disbursement_date)) < date('t', strtotime($disbursement_date)) )
        {
            $paymentStartDate = date('Y-m-d', strtotime(date('Y-m-t', strtotime($disbursement_date)))); //Get the last date of this month as start date
            $paymentEndDate = date('Y-m-d', strtotime(date('Y-m-t', strtotime($disbursement_date. '+' . ($payment_term->loan_payment_term_no_of_months). 'months - 1 week'))));
        }
        else if (date('d', strtotime($disbursement_date)) == date('t', strtotime($disbursement_date)))
        {
            $paymentStartDate = date('Y-m-d', strtotime(date('Y-m-15', strtotime($disbursement_date . '+ 1 week')))); //Get the last date of this month as start date
            $paymentEndDate = date('Y-m-d', strtotime(date('Y-m-t', strtotime($disbursement_date. '+' . ($payment_term->loan_payment_term_no_of_months). 'months - 1 week'))));   
        }

        $payment_periods = $this->get_months($paymentStartDate, $paymentEndDate);
        $paymentPeriod_count = count($payment_periods);

        if (Request::input('filing_service_payment_type') == 0)
        {
            $totalLoan = $loan_application_amount + ($monthlyInterest * $payment_term->loan_payment_term_no_of_months);
            $miscellaneousRate = $filing_fee + $service_fee;
        }
        else if (Request::input('filing_service_payment_type') == 1)
        {
            $totalLoan = $loan_application_amount +  $filing_fee + $service_fee + ($monthlyInterest * $payment_term->loan_payment_term_no_of_months);
            $miscellaneousRate = 0;
        }
        else if (Request::input('filing_service_payment_type') == 2)
        {
            $totalLoan = $loan_application_amount + ($monthlyInterest * $payment_term->loan_payment_term_no_of_months) + ($filing_fee + ($filing_fee * ($interest->loan_interest_rate * .01))) + ($service_fee + ($service_fee * ($interest->loan_interest_rate * .01)));
            $miscellaneousRate = 0;
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
        $loan_application->loan_application_filing_service_payment = Request::input('filing_service_payment_type');
        $loan_application->loan_application_disbursement_date = Request::input('disbursement_date');
        //Relationships
        $loan_application->loan_application_comaker_id1 = Request::input('comaker1_id');
        $loan_application->loan_application_comaker_id2 = Request::input('comaker2_id');
        $loan_application->loan_borrower_id = Request::input('borrower_id');
        $loan_application->payment_term_id = Request::input('payment_term_id');
        $loan_application->loan_interest_id = Request::input('loan_interest_id');
        $loan_application->payment_schedule_id = Request::input('payment_schedule_id');
        $loan_application->company_id = $company->company_id;
        $loan_application->save();

        //Query the id of the recently saved Loan Application
        $loan_application_max = LoanApplication::max('id');

        //Loop through each payment period and place it on to the array for the JSON
        for ($i=0; $i<count($payment_periods); $i++) {
            $payment_collection = new PaymentCollection();
            $payment_collection->is_paid = 0;
            $payment_collection->payment_collection_date = $dt->format('Y-m-d');
            if ($i==0) //Add the Filing Fee and Service Fee upfront (if so)
            {
                $payment_collection->payment_collection_amount = round($periodicRate + $miscellaneousRate, 2);    
            }
            else
            {
                $payment_collection->payment_collection_amount = round($periodicRate, 2);
            }
            $payment_collection->loan_application_id = $loan_application_max;
            $payment_collection->company_id = $company->company_id;

            $payment_collection->save();
        }


        Session::flash('message', 'Loan Application successfully saved! It is now currently pending for approval.');

        return Redirect::to('admin/loan_applications');
    }

    private function get_months($date1, $date2) {

        $time1 = strtotime($date1);
        $time2 = strtotime($date2);

        $my = date('mY', $time2);
        $months = array(date('Y-m-d', $time1));
        $f = '';

        while($time1 < $time2) {

            if (date('d', $time1) == date('t', $time1))
            {
                //Get the 15th date of the next month
                $time1 = strtotime(date('Y-m-15', strtotime(date('Y-m-d', $time1).' + 1 week')));
            }
            else if (date('d', $time1) >= 15 && date('d', $time1) < date('t', $time1))
            {
                //Get the last date of the current month
                $time1 = strtotime(date('Y-m-t', strtotime(date('Y-m-d', $time1))));
            }
            else if (date('d', $time1) < 15)
            {
                //Get the 15th date of the current month
                $time1 = strtotime(date('Y-m-15', $time1));
            }

            array_push($months, date('Y-m-d', $time1));
            /*$time1 = strtotime((date('Y-m-d', $time1).' +15days'));

            if(date('F', $time1) != $f) {
                $f = date('F', $time1);

                if(date('mY', $time1) != $my && ($time1 < $time2))
                    $months[] = date('Y-m-t', $time1);
            }
            */

        }
        return $months;
    }
/*
    =====================================================================
    ------Comment to prevent data snipping in (when not in use) --------
    =====================================================================
*/
    public function saveViaJson(Request $jsonRequest)
    {
        $jsonData = json_decode($jsonRequest->getContent(), true);
        $jsonDataSize = sizeof($jsonData);
        //dd($jsonDataSize);
        for ($i=0; $i<$jsonDataSize; $i++)
        {
            $loan_application_amount = $jsonData[$i]['amount'];
            $filing_fee = $jsonData[$i]['filing_fee'];
            $service_fee = $jsonData[$i]['service_fee'];
            $disbursement_date = $jsonData[$i]['disbursement_date'];
            $payment_term_id = $jsonData[$i]['payment_term_id'];
            $payment_schedule_id = $jsonData[$i]['payment_schedule_id'];
            $interest_id = $jsonData[$i]['loan_interest_id'];
            $filing_service_payment_type = $jsonData[$i]['filing_service_payment_type'];

            //And query those data with ids to get the real meat out of it.
            $payment_term = LoanPaymentTerm::where('id', '=', $payment_term_id)->first();
            $payment_schedule = PaymentSchedule::where('id', '=', $payment_schedule_id)->first();
            $interest = LoanInterest::where('id', '=', $interest_id)->first();

            $periodicRate = 0; //loan monthly/kinsenas/whatever rate
            $miscellaneousRate = 0; //if so happens that filing fee and service fee wouldn't be amortized
            $totalLoan = 0; //total loan (principal + interest)

            $monthlyInterest = $loan_application_amount * ($interest->loan_interest_rate * .01);
            
            $last_day_from_disbursement_date = date('Y-m-t', strtotime($disbursement_date));
            if (date('d', strtotime($disbursement_date)) < 15) //if the disbursement date was less than or equal to the 15th
            {
                $paymentStartDate = date('Y-m-15', strtotime($disbursement_date)); //Get the 15th day of the current month as start date
                $paymentEndDate = date('Y-m-15', strtotime($disbursement_date . '+' . ($payment_term->loan_payment_term_no_of_months) . 'months'));
            }
            else if (date('d', strtotime($disbursement_date)) < date('t', strtotime($disbursement_date)) )
            {
                $paymentStartDate = date('Y-m-d', strtotime(date('Y-m-t', strtotime($disbursement_date)))); //Get the last date of this month as start date
                $paymentEndDate = date('Y-m-d', strtotime(date('Y-m-t', strtotime($disbursement_date. '+' . ($payment_term->loan_payment_term_no_of_months). 'months - 1 week'))));
            }
            else if (date('d', strtotime($disbursement_date)) == date('t', strtotime($disbursement_date)))
            {
                $paymentStartDate = date('Y-m-d', strtotime(date('Y-m-15', strtotime($disbursement_date . '+ 1 week')))); //Get the last date of this month as start date
                $paymentEndDate = date('Y-m-d', strtotime(date('Y-m-t', strtotime($disbursement_date. '+' . ($payment_term->loan_payment_term_no_of_months). 'months - 1 week'))));   
            }

            $payment_periods = $this->get_months($paymentStartDate, $paymentEndDate);
            $paymentPeriod_count = count($payment_periods);

            if ($filing_service_payment_type == 0)
            {
                $totalLoan = $loan_application_amount + ($monthlyInterest * $payment_term->loan_payment_term_no_of_months);
                $miscellaneousRate = $filing_fee + $service_fee;
            }
            else if ($filing_service_payment_type == 1)
            {
                $totalLoan = $loan_application_amount +  $filing_fee + $service_fee + ($monthlyInterest * $payment_term->loan_payment_term_no_of_months);
                $miscellaneousRate = 0;
            }
            else if ($filing_service_payment_type == 2)
            {
                $totalLoan = $loan_application_amount + ($monthlyInterest * $payment_term->loan_payment_term_no_of_months) + ($filing_fee + ($filing_fee * ($interest->loan_interest_rate * .01))) + ($service_fee + ($service_fee * ($interest->loan_interest_rate * .01)));
                $miscellaneousRate = 0;
            }

            $periodicRate = $totalLoan / $paymentPeriod_count;

            /*--------------------------------------------------
                Save the loan application to the database
            ---------------------------------------------------*/
            $loan_application = new LoanApplication();
            $loan_application->loan_application_is_active = 1;
            $loan_application->loan_application_amount = $jsonData[$i]['amount'];
            $loan_application->loan_application_total_amount = round($totalLoan, 2);
            $loan_application->loan_application_interest = round($monthlyInterest, 2);
            $loan_application->loan_application_periodic_rate = round($periodicRate, 2);
            $loan_application->loan_application_purpose = ' ';
            $loan_application->loan_application_status = "Approved";
            $loan_application->loan_application_filing_fee = $jsonData[$i]['filing_fee'];
            $loan_application->loan_application_service_fee = $jsonData[$i]['service_fee'];
            $loan_application->loan_application_filing_service_payment = $jsonData[$i]['filing_service_payment_type'];
            $loan_application->loan_application_disbursement_date = $jsonData[$i]['disbursement_date'];
            //Relationships
            $loan_application->loan_application_comaker_id1 = '1';
            $loan_application->loan_application_comaker_id2 = '2';
            $loan_application->loan_borrower_id = $jsonData[$i]['borrower_id'];
            $loan_application->payment_term_id = $jsonData[$i]['payment_term_id'];
            $loan_application->loan_interest_id = $jsonData[$i]['loan_interest_id'];
            $loan_application->payment_schedule_id = $jsonData[$i]['payment_schedule_id'];
            $loan_application->company_id = $jsonData[$i]['company_id'];
            $loan_application->created_at = $jsonData[$i]['created_at'];
            $loan_application->save();

            //Query the id of the recently saved Loan Application
            $loan_application_max = LoanApplication::max('id');

            //Loop through each payment period and place it on to the array for the JSON
            for ($j=0; $j<$paymentPeriod_count; $j++) {
                $payment_collection = new PaymentCollection();
                $payment_collection->is_paid = 0;
                $payment_collection->payment_collection_date = $payment_periods[$j];
                if ($j==0) //Add the Filing Fee and Service Fee upfront (if so)
                {
                    $payment_collection->payment_collection_amount = round($periodicRate + $miscellaneousRate, 2);    
                }
                else
                {
                    $payment_collection->payment_collection_amount = round($periodicRate, 2);
                }
                $payment_collection->loan_application_id = $loan_application_max;
                $payment_collection->company_id = $jsonData[$j]['company_id'];

                $payment_collection->save();
            }
        }

        return 'JSON SAVED TO DB SUCCESSFUL!';
    }

    public function process_application()
    {
        /*--------------------------------------------------
            Update the current loan application to the database
        ---------------------------------------------------*/
        $loan_application = LoanApplication::find(Request::input('loan_application_id'));

        if (Request::input('change_details') === 'yes')
        {
          //Loan application core details will be updated...

          //So pre-compute first
          //Get all post inputs
          $company = Borrower::where('id', '=', Request::input('borrower_id'))->first();
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

            $periodicRate = 0; //loan monthly/kinsenas/whatever rate
            $miscellaneousRate = 0; //if so happens that filing fee and service fee wouldn't be amortized
            $totalLoan = 0; //total loan (principal + interest)

            $monthlyInterest = $loan_application_amount * ($interest->loan_interest_rate * .01);

            
            $last_day_from_disbursement_date = date('Y-m-t', strtotime($disbursement_date));
            if (date('d', strtotime($disbursement_date)) < 15) //if the disbursement date was less than or equal to the 15th
            {
                $paymentStartDate = date('Y-m-15', strtotime($disbursement_date)); //Get the 15th day of the current month as start date
                $paymentEndDate = date('Y-m-15', strtotime($disbursement_date . '+' . ($payment_term->loan_payment_term_no_of_months) . 'months'));
            }
            else if (date('d', strtotime($disbursement_date)) < date('t', strtotime($disbursement_date)) )
            {
                $paymentStartDate = date('Y-m-d', strtotime(date('Y-m-t', strtotime($disbursement_date)))); //Get the last date of this month as start date
                $paymentEndDate = date('Y-m-d', strtotime(date('Y-m-t', strtotime($disbursement_date. '+' . ($payment_term->loan_payment_term_no_of_months). 'months - 1 week'))));
            }
            else if (date('d', strtotime($disbursement_date)) == date('t', strtotime($disbursement_date)))
            {
                $paymentStartDate = date('Y-m-d', strtotime(date('Y-m-15', strtotime($disbursement_date . '+ 1 week')))); //Get the last date of this month as start date
                $paymentEndDate = date('Y-m-d', strtotime(date('Y-m-t', strtotime($disbursement_date. '+' . ($payment_term->loan_payment_term_no_of_months). 'months - 1 week'))));   
            }

            $payment_periods = $this->get_months($paymentStartDate, $paymentEndDate);
            $paymentPeriod_count = count($payment_periods);

            if (Request::input('filing_service_payment_type') == 0)
            {
                $totalLoan = $loan_application_amount + ($monthlyInterest * $payment_term->loan_payment_term_no_of_months);
                $miscellaneousRate = $filing_fee + $service_fee;
            }
            else if (Request::input('filing_service_payment_type') == 0)
            {
                $totalLoan = $loan_application_amount +  $filing_fee + $service_fee + ($monthlyInterest * $payment_term->loan_payment_term_no_of_months);
                $miscellaneousRate = 0;
            }
            else if (Request::input('filing_service_payment_type') == 0)
            {
                $totalLoan = $loan_application_amount + ($monthlyInterest * $payment_term->loan_payment_term_no_of_months) + ($filing_fee + ($filing_fee * ($interest->loan_interest_rate * .01))) + ($service_fee + ($service_fee * ($interest->loan_interest_rate * .01)));
                $miscellaneousRate = 0;
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
            $loan_application->loan_application_filing_service_payment = Request::input('filing_service_payment_type');
            $loan_application->loan_application_disbursement_date = Request::input('disbursement_date');
            //Relationships
            $loan_application->loan_application_comaker_id1 = Request::input('comaker1_id');
            $loan_application->loan_application_comaker_id2 = Request::input('comaker2_id');
            $loan_application->loan_borrower_id = Request::input('borrower_id');
            $loan_application->payment_term_id = Request::input('payment_term_id');
            $loan_application->loan_interest_id = Request::input('loan_interest_id');
            $loan_application->payment_schedule_id = Request::input('payment_schedule_id');
            $loan_application->company_id = $company->company_id;
            $loan_application->save();

              //Delete previous payment collection to incorporate with the new one... slow but it gets the job done accurately
              //Put it in the string first to prevent injection (even if it's already secure, just to make sure :))
              $sql = 'DELETE FROM payment_collections WHERE loan_application_id='.Request::input('loan_application_id').';';
              DB::statement($sql);

            //Loop through each payment period and place it on to the array for the JSON
            for ($i=0; $i<count($payment_periods); $i++) {
                $payment_collection = new PaymentCollection();
                $payment_collection->is_paid = 0;
                $payment_collection->payment_collection_date = $dt->format('Y-m-d');
                if ($i==0) //Add the Filing Fee and Service Fee upfront (if so)
                {
                    $payment_collection->payment_collection_amount = round($periodicRate + $miscellaneousRate, 2);    
                }
                else
                {
                    $payment_collection->payment_collection_amount = round($periodicRate, 2);
                }
                $payment_collection->loan_application_id = Request::input('loan_application_id');
                $payment_collection->company_id = $company->company_id;

                $payment_collection->save();
            }
        }//if(change_details) === yes

        //Now check condition if the user approved the loan or not, regardless.
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

        //Finally saving the updated loan application
        $loan_application->save();


        return Redirect::to('admin/loan_applications');
    }


/*==============================================================
                        AJAX-loaded data
==============================================================*/

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
                ->select('loan_applications.*');
            return Datatables::of($loan_applications)
                ->add_column('Actions', 
                    '<div class="dropdown">
                    <button class="btn btn-info btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Report
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href=\'{{ url(\'admin/loan_applications/generate/\' . $id )}}\' target=\'_blank\'>Generate Loan Application</a></li>
                            <li><a href=\'{{ url(\'admin/loan_applications/statement_of_account/\' . $id )}}\' target=\'_blank\'>Statement of Account</a></li>
                            <li><a href=\'{{ url(\'admin/loan_applications/promissory_note/\' . $id )}}\' target=\'_blank\'>Promissory Note</a></li>
                            <li><a href=\'{{ url(\'admin/loan_applications/payment_schedule/\' . $id )}}\' target=\'_blank\'>Payment Schedule</a></li>
                        </ul>
                    </div>')
                ->make(true);
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
                ->select('loan_applications.*');
            return Datatables::of($loan_applications)
                ->add_column('Actions', 
                    '<div class="dropdown">
                    <button class="btn btn-info btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Report
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href=\'{{ url(\'admin/loan_applications/generate/\' . $id )}}\' target=\'_blank\'>Generate Loan Application</a></li>
                            <li><a href=\'{{ url(\'admin/loan_applications/statement_of_account/\' . $id )}}\' target=\'_blank\'>Statement of Account</a></li>
                            <li><a href=\'{{ url(\'admin/loan_applications/promissory_note/\' . $id )}}\' target=\'_blank\'>Promissory Note</a></li>
                            <li><a href=\'{{ url(\'admin/loan_applications/payment_schedule/\' . $id )}}\' target=\'_blank\'>Payment Schedule</a></li>
                        </ul>
                    </div>')
                ->make(true);
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
                ->select('loan_applications.*');
            return Datatables::of($loan_applications)
                ->add_column('Actions', '<a href=\'{{ url(\'admin/loan_applications/\' . $id )}}\' class=\'btn btn-primary btn-xs\'> Approve/Decline </a>')
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
                ->select('loan_applications.*');
            return Datatables::of($loan_applications)
                ->add_column('Actions', '<a href=\'{{ url(\'admin/loan_applications/\' . $id )}}\' class=\'btn btn-primary btn-xs\'> Approve/Decline </a>')
                ->make();
        }
    }

    public function archives_data()
    {
        if (Auth::user()->company->id == 1)
        {
            $loan_applications = LoanApplication::where('loan_application_is_active', '=', '0')
                ->with('loan_borrower')
                ->with('loan_interest')
                ->with('loan_payment_term')
                ->with('loan_borrower.company')
                ->select('loan_applications.*');
            return Datatables::of($loan_applications)
                ->add_column('Actions', 
                    '<div class="dropdown">
                    <button class="btn btn-info btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Report
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href=\'{{ url(\'admin/loan_applications/statement_of_account/\' . $id )}}\' target=\'_blank\'>Statement of Account</a></li>
                            <li><a href=\'{{ url(\'admin/loan_applications/promissory_note/\' . $id )}}\' target=\'_blank\'>Promissory Note</a></li>
                        </ul>
                    </div>')
                ->make(true);
        }
        else
        {
            $loan_applications = LoanApplication::where('loan_application_is_active', '=', '0')
                ->with(['loan_borrower' => function($q) {
                    $q->where('company_id', '=', Auth::user()->company->id);
                }])
                ->with('loan_interest')
                ->with('loan_payment_term')
                ->with('loan_borrower.company')
                ->select('loan_applications.*');
            return Datatables::of($loan_applications)
                ->add_column('Actions', 
                    '<div class="dropdown">
                    <button class="btn btn-info btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Report
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href=\'{{ url(\'admin/loan_applications/statement_of_account/\' . $id )}}\' target=\'_blank\'>Statement of Account</a></li>
                            <li><a href=\'{{ url(\'admin/loan_applications/promissory_note/\' . $id )}}\' target=\'_blank\'>Promissory Note</a></li>
                        </ul>
                    </div>')
                ->make(true);
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

        $periodicRate = 0; //loan monthly/kinsenas/whatever rate
        $miscellaneousRate = 0; //if so happens that filing fee and service fee wouldn't be amortized
        $totalLoan = 0; //total loan (principal + interest)

        $monthlyInterest = $loan_application_amount * ($interest->loan_interest_rate * .01);

        
        $last_day_from_disbursement_date = date('Y-m-t', strtotime($disbursement_date));
        if (date('d', strtotime($disbursement_date)) < 15) //if the disbursement date was less than or equal to the 15th
        {
            $paymentStartDate = date('Y-m-15', strtotime($disbursement_date)); //Get the 15th day of the current month as start date
            $paymentEndDate = date('Y-m-15', strtotime($disbursement_date . '+' . ($payment_term->loan_payment_term_no_of_months) . 'months'));
        }
        else if (date('d', strtotime($disbursement_date)) < date('t', strtotime($disbursement_date)) )
        {
            $paymentStartDate = date('Y-m-d', strtotime(date('Y-m-t', strtotime($disbursement_date)))); //Get the last date of this month as start date
            $paymentEndDate = date('Y-m-d', strtotime(date('Y-m-t', strtotime($disbursement_date. '+' . ($payment_term->loan_payment_term_no_of_months). 'months - 1 week'))));
        }
        else if (date('d', strtotime($disbursement_date)) == date('t', strtotime($disbursement_date)))
        {
            $paymentStartDate = date('Y-m-d', strtotime(date('Y-m-15', strtotime($disbursement_date . '+ 1 week')))); //Get the last date of this month as start date
            $paymentEndDate = date('Y-m-d', strtotime(date('Y-m-t', strtotime($disbursement_date. '+' . ($payment_term->loan_payment_term_no_of_months). 'months - 1 week'))));   
        }

        $payment_periods = $this->get_months($paymentStartDate, $paymentEndDate);
        $paymentPeriod_count = count($payment_periods);

        if (Request::input('filing_service_payment_type') == 0)
        {
            $totalLoan = $loan_application_amount + ($monthlyInterest * $payment_term->loan_payment_term_no_of_months);
            $miscellaneousRate = $filing_fee + $service_fee;
        }
        else if (Request::input('filing_service_payment_type') == 1)
        {
            $totalLoan = $loan_application_amount +  $filing_fee + $service_fee + ($monthlyInterest * $payment_term->loan_payment_term_no_of_months);
            $miscellaneousRate = 0;
        }
        else if (Request::input('filing_service_payment_type') == 2)
        {
            $totalLoan = $loan_application_amount + ($monthlyInterest * $payment_term->loan_payment_term_no_of_months) + ($filing_fee + ($filing_fee * ($interest->loan_interest_rate * .01))) + ($service_fee + ($service_fee * ($interest->loan_interest_rate * .01)));
            $miscellaneousRate = 0;
        }

        $periodicRate = $totalLoan / $paymentPeriod_count;

        //Query the id of the recently saved Loan Application
        $loan_application_max = LoanApplication::max('id');

        $periodic_rates = array();
            //Loop through each payment period and place it on to the array for the JSON
        for ($i=0; $i<count($payment_periods); $i++)
        {
            if ($i==0) //Add the Filing Fee and Service Fee upfront (if so)
            {
                array_push($periodic_rates, round($periodicRate + $miscellaneousRate, 2));    
            }
            else
            {
                array_push($periodic_rates, round($periodicRate, 2));  
            }
        }

        $data = array(
            "payment_periods" => $payment_periods,
            "total_loan" => round($totalLoan + $miscellaneousRate, 2),
            "monthly_interest" => round($monthlyInterest, 2),
            "payment_count" => $paymentPeriod_count,
            "periodic_rates" => $periodic_rates
            );

        return json_encode($data);
    }
}

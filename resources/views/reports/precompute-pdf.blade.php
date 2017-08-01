<?php
use App\Borrower;

//Get borrower and co-maker from database
$loan_borrower = Borrower::where('id', '=', Session::get('loan_borrower_id'))->first();
$comaker1 = Borrower::where('id', '=', Session::get('comaker1_id'))->first();
$comaker2 = Borrower::where('id', '=', Session::get('comaker2_id'))->first();
//Came from form input
$company = Session::get('company');
$principal = Session::get('principal');
$purpose = Session::get('purpose');
$filing_fee = Session::get('filing_fee');
$service_fee = Session::get('service_fee');
$filing_service_payment_type = Session::get('filing_service_payment_type');
$disbursement_date = Session::get('disbursement_date');
$collection_date = Session::get('collection_date');
$payment_term = Session::get('payment_term');
$payment_schedule = Session::get('payment_schedule');
//Came from pre-compute
$interest_name = Session::get('interest_name');
$interest_amount = Session::get('interest_amount');
$total_fees = Session::get('total_fees');
$total_loan = Session::get('total_loan');
$payment_count = Session::get('payment_count');
//Arrays
$payment_periods = json_decode(Session::get('payment_periods'));
$periodic_rates = json_decode(Session::get('periodic_rates'));
$periodic_principal_rates = json_decode(Session::get('periodic_principal_rates'));
$periodic_interest_rates = json_decode(Session::get('periodic_interest_rates'));
$periodic_filing_fee = json_decode(Session::get('periodic_filing_fee'));
$periodic_service_fee = json_decode(Session::get('periodic_service_fee'));

//$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
$payment_periods_length = count($payment_periods);
?>


<!DOCTYPE html5>
<html>
      <head>
            <title>[PREVIEW] Loan Application | Loan Management System</title>

            <style type="text/css">
                  .div_head
                  {
                    text-align: center;
                    color: white;
                    background-color: black;
                    width: 100%;
                    height: 2%;
                  }
                  table
                  {
                      font-size: 10;
                      border-collapse: collapse;
                      page-break-inside: auto;
                  }
                  tr
                  { 
                      page-break-inside: avoid;
                      page-break-after: auto; 
                  }
                  td
                  {
                      font-family: "Arial", helvetica, sans-serif;
                      padding: 5px;
                  }
                  p, strong, h3
                  {
                      font-family: "Arial", helvetica, sans-serif;
                  }
                  img 
                  {
                      position: absolute;
                      left: 70px;
                      top: 5px;
                  }
                  .unitlogo
                  {
                      position: absolute;
                      left: 960px;
                      top: 16px;
                  }
                  .label 
                  {
                      display: inline;
                      padding: .2em .6em .3em;
                      font-size: 60%;
                      font-family: "Arial", helvetica, sans-serif;
                      font-weight: bold;
                      line-height: 1;
                      color: #fff;
                      text-align: center;
                      white-space: nowrap;
                      vertical-align: baseline;
                      border-radius: .25em;
                  }
                  .label-default 
                  {
                      background-color: #777;
                  }
                  .footer 
                  {
                      width: 100%;
                      text-align: right;
                      font-size: 10px;
                      position: fixed;
                      bottom: 0px;
                      counter-increment:pages;
                  }
                  .pagenum:before 
                  {
                      content: "Page " counter(page);
                  }
          </style>
      </head>

      <body>
            <center><img src="{{ asset('img/mooloans_logo_web.jpg') }}" width="30%" height="30%" style="position: inherit;"></center>
            <div class="loaninfo">
                  <center><p><strong>[PREVIEW] LOAN APPLICATION</strong></p></center>

                  <table border="1" width="100%">
                    <tr>
                      <td><sup>Application Date:</sup></td>
                      <?php
                        $timestamp = strtotime(date('Y-m-d'));
                      ?>
                      <td><strong>*{{ date('F j, Y', $timestamp) }}</strong></td>
                      <td><sup>Application ID:</sup></td>
                      <td><strong>*Preview only</strong></td>
                    </tr>
                  </table>
                  <br>
                  <hr>

                  <div class="div_head">
                    <strong>LOAN AMOUNT REQUEST</strong>
                  </div>
                  
                  <table border="1" width="100%">
                    <tr>
                      <td width="25%">
                        <sup>Principal Amount</sup>
                        <br>
                        <strong>{{ number_format($principal, 2) }}</strong>
                      </td>
                      <td width="25%">
                        <sup>Interest</sup>
                        <br>
                        <strong>{{ number_format($interest_amount, 2) }}</strong>
                      </td>
                      <td width="25%">
                        <sup>Interest Name</sup>
                        <br>
                        <strong>{{ $interest_name }}</strong>
                      </td>
                    </tr>

                    <tr>
                      <td width="70%">
                        <sup>Filing Fee</sup>
                        <br>
                        <strong>{{ number_format($filing_fee, 2) }}</strong>
                      </td>
                      <td width="30%" colspan="2">
                        <sup>Service Fee</sup>
                        <br>
                        <strong>{{ number_format($service_fee, 2) }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td width="60%">
                        <sup>Due Total</sup>
                        <br>
                        <strong>PHP {{ number_format($total_loan, 2) }}</strong>
                      </td>
                      <td width="40%" colspan="2">
                        <sup>Payable in</sup>
                        <br>
                        <strong>{{ $payment_periods_length }} cycles</strong>
                      </td>
                    </tr>
                    <tr>
                      <td width="70%">
                        <sup>Filing Fee & Service Fee Payment Type</sup>
                        <br>
                        <strong>
                          @if($filing_service_payment_type == 0)
                            One-time Payment
                          @elseif($filing_service_payment_type == 1)
                            Amortized without Interest
                          @elseif($filing_service_payment_type == 2)
                            Amortized with Interest
                          @endif
                        </strong>
                      </td>
                      <td width="30%" colspan="2">
                        <sup>Term</sup>
                        <br>
                        <strong>{{ $payment_term }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td width="60%" colspan="3">
                        <sup>Loan Purpose</sup>
                        <br>
                        <strong>{{ $purpose }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td width="70%">
                        <sup>Disbursement Date: </sup>
                        <br>
                        <strong>{{ date('F j, Y', strtotime($disbursement_date)) }}</strong>
                      </td>
                      <td width="30%" colspan="2">
                        <sup>Maturity Date: </sup>
                        <br>
                        <strong>{{ date('F j, Y', strtotime($payment_periods[$payment_periods_length-1])) }}</strong>
                      </td>
                    </tr>
                  </table>
                  <br>

                  <div class="div_head">
                    <strong>PERSONAL INFORMATION</strong>
                  </div>
                  
                  <table border="1" width="100%">
                    <tr>
                      <td width="25%">
                        <sup>Client ID</sup>
                        <br>
                        <strong>{{ $loan_borrower->id }}</strong>
                      </td>
                      <td width="25%">
                        <sup>Employee Type</sup>
                        <br>
                        <strong>{{ $loan_borrower->borrower_type }}</strong>
                      </td>
                      <td width="50%">
                        <sup>Full Name</sup>
                        <br>
                        <strong>{{ $loan_borrower->borrower_last_name }}, {{ $loan_borrower->borrower_first_name }} {{ $loan_borrower->borrower_middle_name }}</strong>
                      </td>
                    </tr>

                    <tr>
                      <td width="70%" colspan="2">
                        <sup>Home Address</sup>
                        <br>
                          <strong>{{ $loan_borrower->borrower_home_address }}</strong>
                      </td>
                      <td width="30%">
                        <sup>E-mail Address</sup>
                        <br>
                          <strong>{{ $loan_borrower->borrower_email }}</strong>
                      </td>
                    </tr>

                    <tr>
                      <td width="50%" colspan="2">
                        <sup>If Married, Name of Spouse</sup>
                        <br>
                        @if($loan_borrower->borrower_spouse_name)
                          <strong>{{ $loan_borrower->borrower_spouse_name }}</strong>
                        @else
                          <strong>N/A</strong>
                        @endif
                      </td>
                      <td width="25%">
                        <sup>No. of Children</sup>
                        <br>
                        @if($loan_borrower->borrower_no_of_children)
                          <strong>{{ $loan_borrower->borrower_no_of_children }}</strong>
                        @else
                          <strong>N/A</strong>
                        @endif
                      </td>
                    </tr>

                    <tr>
                      <td width="50%" colspan="2">
                        <sup>Company</sup>
                        <br>
                        <strong>{{ $company }}</strong>
                      </td>
                      <td width="50%">
                        <sup>Birthday</sup>
                        <br>
                        <strong>{{ $loan_borrower->borrower_birth_date }}</strong>
                      </td>
                    </tr>
                  </table>
                  <br>

                  <div class="div_head">
                    <strong>CO-MAKER 1</strong>
                  </div>
                  
                  <table border="1" width="100%">
                    <tr>
                      <td width="25%">
                        <sup>Client ID</sup>
                        <br>
                        <strong>{{ @$comaker1->id }}</strong>
                      </td>
                      <td width="25%">
                        <sup>Employee Type</sup>
                        <br>
                        <strong>{{ @$comaker1->borrower_type }}</strong>
                      </td>
                      <td width="50%">
                        <sup>Full Name</sup>
                        <br>
                        <strong>{{ @$comaker1->borrower_last_name }}, {{ @$comaker1->borrower_first_name }} {{ @$comaker1->borrower_middle_name }}</strong>
                      </td>
                    </tr>
                  </table>
                  <br>

                  <div class="div_head">
                    <strong>CO-MAKER 2</strong>
                  </div>
                  <table border="1" width="100%">
                    <tr>
                      <td width="25%">
                        <sup>Client ID</sup>
                        <br>
                        <strong>{{ @$comaker2->id }}</strong>
                      </td>
                      <td width="25%">
                        <sup>Employee Type</sup>
                        <br>
                        <strong>{{ @$comaker2->borrower_type }}</strong>
                      </td>
                      <td width="50%">
                        <sup>Full Name</sup>
                        <br>
                        <strong>{{ @$comaker2->borrower_last_name }}, {{ @$comaker2->borrower_first_name }} {{ @$comaker2->borrower_middle_name }}</strong>
                      </td>
                    </tr>
                  </table>
                  <br>

                  <div class="div_head">
                    <strong>SCHEDULE OF PAYMENTS</strong>
                  </div>
                  <table border="1" width="100%">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th align="right">Amount</th>
                        <th align="right">Principal</th>
                        <th align="right">Interest</th>
                        <th align="right">Filing Fee</th>
                        <th align="right">Service Fee</th>
                      </tr>
                    </thead>    
                    <tbody>
                      @for($i = 0; $i < $payment_periods_length; $i++)
                      <tr>
                        <td align="center">{{ $payment_periods[$i] }}</td>
                        <td align="right">PHP {{ number_format($periodic_rates[$i], 2) }}</td>
                        <td align="right">PHP {{ number_format($periodic_principal_rates[$i], 2) }}</td>
                        <td align="right">PHP {{ number_format($periodic_interest_rates[$i], 2) }}</td>
                        <td align="right">PHP {{ number_format($periodic_filing_fee[$i], 2) }}</td>
                        <td align="right">PHP {{ number_format($periodic_service_fee[$i], 2) }}</td>
                      </tr>
                      @endfor
                    </tbody>
                  </table>
            </div>
         <p><i>For visual report purposes only, the following information you see here aren't saved to the database.</i></p>
      </body>
</html>
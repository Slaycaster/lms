<?php

use App\LoanApplication;
use App\PaymentSchedule;
use App\PaymentCollection;

	$timestamp = time()+date("Z");
	$today = gmdate("F j, Y",$timestamp);
	//$date = Session::get('date', $today);
	$application_id = Session::get('application_id', 1);

	$loan_applications = LoanApplication::where('id', '=', $application_id)
            ->with('loan_borrower')
            ->with('comaker1')
            ->with('comaker2')
            ->with('loan_borrower.company')
            ->with('loan_interest')
            ->with('loan_payment_term')
            ->with('payment_collections')
            ->get();

  $maturity_date = PaymentCollection::where('loan_application_id', '=', $application_id)
              ->orderBy('payment_collection_date', 'desc')
              ->first();

  $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);

?>


<!DOCTYPE html5>
<html>
      <head>
            <title>Loan Application | Loan Management System</title>

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
          @foreach($loan_applications as $loan_application)

            <div class="loaninfo">
                  <center><p><strong>LOAN APPLICATION FORM</strong></p></center>

                  <table border="1" width="100%">
                    <tr>
                      <td><sup>Application Date:</sup></td>
                      <?php
                        $timestamp = strtotime($loan_application->created_at);
                      ?>
                      <td><strong>{{ date('F j, Y', $timestamp) }}</strong></td>
                      <td><sup>Application ID:</sup></td>
                      <td><strong>{{ $loan_application->id }}</strong></td>
                    </tr>
                  </table>
                  <br>
                  <hr>

                  <div class="div_head">
                    <strong>LOAN AMOUNT REQUEST</strong>
                  </div>
                  
                  <table border="1" width="100%">
                    <tr>
                      <td width="70%">
                        <sup>Amount in Words</sup>
                        <br>
                        <strong>{{ ucwords($f->format($loan_application->loan_application_amount)) }} Pesos</strong>
                      </td>
                      <td width="30%">
                        <sup>PHP</sup>
                        <br>
                        <strong>{{ number_format($loan_application->loan_application_amount, 2) }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td width="70%">
                        <sup>Filing Fee</sup>
                        <br>
                        <strong>{{ number_format($loan_application->loan_application_filing_fee, 2) }}</strong>
                      </td>
                      <td width="30%">
                        <sup>Service Fee</sup>
                        <br>
                        <strong>{{ number_format($loan_application->loan_application_service_fee, 2) }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td width="70%">
                        <sup>Filing Fee & Service Fee Payment Type</sup>
                        <br>
                        <strong>
                          @if($loan_application->loan_application_filing_service_payment == 0)
                            One-time Payment
                          @elseif($loan_application->loan_application_filing_service_payment == 1)
                            Amortized without Interest
                          @elseif($loan_application->loan_application_filing_service_payment == 2)
                            Amortized with Interest
                          @endif
                        </strong>
                      </td>
                      <td width="30%">
                      </td>
                    </tr>
                    <tr>
                      <td width="60%">
                        <sup>Loan Purpose</sup>
                        <br>
                        <strong>{{ $loan_application->loan_application_purpose }}</strong>
                      </td>
                      <td width="20%">
                        <sup>Term</sup>
                        <br>
                        <strong>{{ $loan_application->loan_payment_term->loan_payment_term_name }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td width="70%">
                        <sup>Disbursement Date: </sup>
                        <br>
                        <strong>{{ date('F j, Y', strtotime($loan_application->loan_application_disbursement_date)) }}</strong>
                      </td>
                      <td width="30%">
                        <sup>Maturity Date: </sup>
                        <br>
                        <strong>{{ date('F j, Y', strtotime($maturity_date->payment_collection_date)) }}</strong>
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
                        <strong>{{ $loan_application->loan_borrower->id }}</strong>
                      </td>
                      <td width="25%">
                        <sup>Employee Type</sup>
                        <br>
                        <strong>{{ $loan_application->loan_borrower->borrower_type }}</strong>
                      </td>
                      <td width="50%">
                        <sup>Full Name</sup>
                        <br>
                        <strong>{{ $loan_application->loan_borrower->borrower_last_name }}, {{ $loan_application->loan_borrower->borrower_first_name }} {{ $loan_application->loan_borrower->borrower_middle_name }}</strong>
                      </td>
                    </tr>

                    <tr>
                      <td width="70%" colspan="2">
                        <sup>Home Address</sup>
                        <br>
                          <strong>{{ $loan_application->loan_borrower->borrower_home_address }}</strong>
                      </td>
                      <td width="30%">
                        <sup>E-mail Address</sup>
                        <br>
                          <strong>{{ $loan_application->loan_borrower->borrower_email }}</strong>
                      </td>
                    </tr>

                    <tr>
                      <td width="50%" colspan="2">
                        <sup>If Married, Name of Spouse</sup>
                        <br>
                        @if($loan_application->loan_borrower->borrower_spouse_name)
                          <strong>{{ $loan_application->loan_borrower->borrower_spouse_name }}</strong>
                        @else
                          <strong>N/A</strong>
                        @endif
                      </td>
                      <td width="25%">
                        <sup>No. of Children</sup>
                        <br>
                        @if($loan_application->loan_borrower->borrower_no_of_children)
                          <strong>{{ $loan_application->loan_borrower->borrower_no_of_children }}</strong>
                        @else
                          <strong>N/A</strong>
                        @endif
                      </td>
                    </tr>

                    <tr>
                      <td width="50%" colspan="2">
                        <sup>Company</sup>
                        <br>
                        <strong>{{ $loan_application->company->company_name }}</strong>
                      </td>
                      <td width="50%">
                        <sup>Birthday</sup>
                        <br>
                        <strong>{{ $loan_application->loan_borrower->borrower_birth_date }}</strong>
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
                        <strong>{{ @$loan_application->comaker1->id }}</strong>
                      </td>
                      <td width="25%">
                        <sup>Employee Type</sup>
                        <br>
                        <strong>{{ @$loan_application->comaker1->borrower_type }}</strong>
                      </td>
                      <td width="50%">
                        <sup>Full Name</sup>
                        <br>
                        <strong>{{ @$loan_application->comaker1->borrower_last_name }}, {{ @$loan_application->comaker1->borrower_first_name }} {{ $loan_application->comaker1->borrower_middle_name }}</strong>
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
                        <strong>{{ @$loan_application->comaker2->id }}</strong>
                      </td>
                      <td width="25%">
                        <sup>Employee Type</sup>
                        <br>
                        <strong>{{ @$loan_application->comaker2->borrower_type }}</strong>
                      </td>
                      <td width="50%">
                        <sup>Full Name</sup>
                        <br>
                        <strong>{{ @$loan_application->comaker2->borrower_last_name }}, {{ @$loan_application->comaker2->borrower_first_name }} {{ @$loan_application->comaker2->borrower_middle_name }}</strong>
                      </td>
                    </tr>
                  </table>
                  <br>

                  <p style="font-size: 12px;">I hereby allow {{ $loan_application->company->company_code }} to hold the amount of PHP _________________ in my {{ $loan_application->company->company_code }} Deposit account to increase my Loan Entitlement and/or lower my lending rate. This amount shall be not be withdrawn during the loan period until maturity or when the loan balance is fully paid.<br>I hereby certify that all statement made here and on the Promissory Note are true and completed for the purpose of obtaining a loan from Moo Loans Inc. Should the loan be approved, I authorize {{ $loan_application->company->company_code }} personnel to draw from my salary account the loan payment required as per amortization schedule indicated in the Promissory Note.</p>
                  <br>

                  <table border="1" width="100%">
                    <tr>
                      <td width="80%">
                        <br>
                        <sub>Borrower's Name and Signature</sub>
                      </td>
                      <td width="20%">
                        <br>
                        <sub>Date</sub>
                      </td>
                    </tr>

                    <tr>
                      <td width="80%">
                        <br>
                        <sub>Co-Maker's 1 Name and Signature</sub>
                      </td>
                      <td width="20%">
                        <br>
                        <sub>Date</sub>
                      </td>
                    </tr>

                    <tr>
                      <td width="80%">
                        <br>
                        <sub>Co-Maker's 2 Name and Signature</sub>
                      </td>
                      <td width="20%">
                        <br>
                        <sub>Date</sub>
                      </td>
                    </tr>

                    <tr>
                      <td width="80%">
                        <br>
                        <sub>Endorsed by: {{ $loan_application->company->company_code }} Team Head, Name and Signature</sub>
                      </td>
                      <td width="20%">
                        <br>
                        <sub>Date</sub>
                      </td>
                    </tr>
                  </table>
            </div>
          @endforeach 
         
      </body>
</html>
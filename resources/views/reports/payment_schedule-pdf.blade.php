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
            ->with('payment_schedule')
            ->get();

    $loan_balance = PaymentCollection::where('loan_application_id', '=', $application_id)
        ->where('is_paid', '=', '0')
        ->sum(DB::raw('payment_collection_principal_amount + payment_collection_interest_amount'));

    $total_collection_cycles = PaymentCollection::where('loan_application_id', '=', $application_id)
        ->count();

    $payment_collections = PaymentCollection::where('loan_application_id', '=', $application_id)
        ->get();

    $trans_number = 1;
    $first_repayment = true;
?>


<!DOCTYPE html5>
<html>
      <head>
            <title>Schedule of Payments | Loan Management System</title>

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
          <?php $balance_amount = $loan_application->loan_application_amount; ?>

            <div class="loaninfo">
                  <center><p><strong>SCHEDULE OF PAYMENTS</strong></p></center>

                  <table border="1" width="100%">
                    <tr>
                      <td width="50%">
                        <sup>Client ID</sup>
                        <br>
                        <strong>{{ $loan_application->loan_borrower->id }}</strong>
                      </td>
                      <td width="50%">
                        <sup>Loan Number</sup>
                        <br>
                        <strong>{{ $loan_application->id }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td width="50%">
                        <sup>Name</sup>
                        <br>
                        <strong>{{ $loan_application->loan_borrower->borrower_last_name }}, {{ $loan_application->loan_borrower->borrower_first_name }} {{ $loan_application->loan_borrower->borrower_middle_name }}</strong>
                      </td>
                      <td width="50%">
                        <sup>Statement Date</sup>
                        <br>
                        <strong>{{ date('F j, Y', strtotime($today)) }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td width="50%">
                        <sup>Address</sup>
                        <br>
                        <strong>{{ $loan_application->loan_borrower->borrower_home_address }}</strong>
                      </td>
                      <td width="50%">
                        <sup>Balance</sup>
                        <br>
                        <strong>PHP {{ number_format($loan_balance,2) }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td width="50%" colspan="2">
                        <sup>Company</sup>
                        <br>
                        <strong>{{ $loan_application->loan_borrower->company->company_name }}</strong>
                      </td>
                    </tr>
                  </table>
                  <br>

                  <div class="div_head">
                    <strong>LOAN DETAILS</strong>
                  </div>
                  
                  <table border="1" width="100%">
                    <tr>
                      <td width="50%">
                        <sup>Principal Amount</sup>
                        <br>
                        <strong>PHP {{ number_format($loan_application->loan_application_amount, 2) }}</strong>
                      </td>
                      <td width="50%">
                        <sup>Collection Cycle</sup>
                        <br>
                        <strong>{{ $loan_application->payment_schedule->payment_schedule_name }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td width="50%">
                        <sup>Tenor</sup>
                        <br>
                        <strong>{{ $loan_application->loan_payment_term->loan_payment_term_no_of_months }} months</strong>
                      </td>
                      <td width="50%">
                        <sup>Collection Amount per Cycle</sup>
                        <br>
                        <strong>{{ number_format($loan_application->loan_application_periodic_rate, 2) }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td width="50%">
                        <sup>Interest Rate</sup>
                        <br>
                        <strong>{{ $loan_application->loan_interest->loan_interest_name }}</strong>
                      </td>
                      <td width="50%">
                      </td>
                    </tr>
                    <tr>
                      <td width="50%">
                        <sup>Disbursement Date</sup>
                        <br>
                        <strong>{{ date('F j, Y', strtotime($loan_application->loan_application_disbursement_date)) }}</strong>
                      </td>
                      <td width="50%">
                        <sup>Start Collection Date</sup>
                        <br>
                        <strong>{{ date('F j, Y', strtotime($loan_application->loan_application_collection_date))  }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td width="50%">
                        <sup>Filing Fee</sup>
                        <br>
                        <strong>PHP {{ number_format($loan_application->loan_application_filing_fee,2) }}</strong>
                      </td>
                      <td width="20%">
                        <sup>Service Fee</sup>
                        <br>
                        <strong>PHP {{ number_format($loan_application->loan_application_service_fee,2) }}</strong>
                      </td>
                    </tr>
                    <tr>
                      <td width="50%">
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
                      <td width="50%">
                      </td>
                    </tr>
                  </table>
                  <br>

                  <div class="div_head">
                    <strong>TRANSACTIONS</strong>
                  </div>
                  
                  <table border="1" width="100%">
                    <thead>
                      <tr>
                        <td align="center"><strong>Trans #</strong></td>
                        <td align="left"><strong>Date</strong></td>
                        <td><strong>Description</strong></td>
                        <td align="right"><strong>Amount</strong></td>
                        <td align="right"><strong>Balance</strong></td>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Loan Disbursement -->
                      <tr>
                        <td align="center">{{ $trans_number }}</td> <?php $trans_number++; ?>
                        <td align="left">{{ date('F j, Y', strtotime($loan_application->loan_application_disbursement_date)) }}</td>
                        <td>Loan Disbursement</td>
                        <td align="right">+{{ number_format($loan_application->loan_application_amount,2) }}</td>
                        <td align="right">{{ number_format($loan_application->loan_application_amount,2) }}</td>
                      </tr>
                      @if($loan_application->loan_application_filing_service_payment == 0)
                      <!-- Service Fee -->
                      <tr>
                        <td align="center">{{ $trans_number }}</td> <?php $trans_number++; ?>
                        <td align="left">{{ date('F j, Y', strtotime($loan_application->loan_application_collection_date))  }}</td>
                        <td>Service Fee</td>
                        <td align="right">+{{ number_format($loan_application->loan_application_service_fee,2) }}</td>
                        <?php
                          $balance_amount += $loan_application->loan_application_service_fee;
                        ?>
                        <td align="right">{{ number_format($balance_amount , 2) }}</td>
                      </tr>
                      <!-- Filing Fee -->
                      <tr>
                        <td align="center">{{ $trans_number }}</td> <?php $trans_number++; ?>
                        <td align="left">{{ date('F j, Y', strtotime($loan_application->loan_application_collection_date)) }}</td>
                        <td>Filing Fee</td>
                        <td align="right">+{{ number_format($loan_application->loan_application_filing_fee,2) }}</td>
                        <?php
                          $balance_amount += $loan_application->loan_application_filing_fee;
                        ?>
                        <td align="right">{{ number_format($balance_amount , 2) }}</td>
                      </tr>
                      @endif

                    @foreach($payment_collections as $payment_collection)
                          
                          <!-- Interest -->
                          <tr>
                            <td align="center">{{ $trans_number }}</td> <?php $trans_number++; ?>
                            <td align="left">{{ date('F j, Y', strtotime($payment_collection->payment_collection_date)) }}</td>
                            <td>Interest</td>
                            <td align="right">+{{ number_format( $payment_collection->payment_collection_interest_amount, 2)  }} </td>
                            <?php
                              $balance_amount += $payment_collection->payment_collection_interest_amount;
                            ?>
                            <td align="right">{{ number_format($balance_amount,2) }}</td>
                          </tr>

                       @if($payment_collection->is_paid == 1)
                          <!-- Repayment - Interest -->
                          <tr>
                            <td align="center">{{ $trans_number }}</td> <?php $trans_number++; ?>
                            <td align="left">{{ date('F j, Y', strtotime($payment_collection->payment_collection_date)) }}</td>
                            <td>Repayment - Interest</td>
                            <td align="right">-{{ number_format( $payment_collection->payment_collection_interest_amount, 2)  }} </td>
                            <?php
                              $balance_amount -= $payment_collection->payment_collection_interest_amount;
                            ?>
                            <td align="right">{{ number_format($balance_amount,2) }}</td>
                          </tr>
                        @endif

                      @if($loan_application->loan_application_filing_service_payment == 0 && $first_repayment)
                          <!-- Repayment - Service Fee -->
                          <tr>
                            <td align="center">{{ $trans_number }}</td> <?php $trans_number++; ?>
                            <td align="left">{{ date('F j, Y', strtotime($payment_collection->payment_collection_date)) }}</td>
                            <td>Repayment - Service Fee</td>
                            <td align="right">-{{ number_format($loan_application->loan_application_service_fee,2) }}</td>
                            <?php $balance_amount -= $loan_application->loan_application_service_fee; ?>
                            <td align="right">{{ number_format($balance_amount,2) }}</td>
                          </tr>

                          <!-- Repayment - Filing Fee -->
                          <tr>
                            <td align="center"> {{ $trans_number }} </td> <?php $trans_number++; ?>
                            <td align="left">{{ date('F j, Y', strtotime($payment_collection->payment_collection_date)) }}</td>
                            <td>Repayment - Filing Fee</td>
                            <td align="right">-{{ number_format($loan_application->loan_application_filing_fee,2) }}</td>
                            <?php $balance_amount -= $loan_application->loan_application_filing_fee; ?>
                            <td align="right">{{ number_format($balance_amount,2) }}</td>
                          </tr>
                          <?php
                            $first_repayment = false;
                          ?>
                      @endif <!-- loan_application_filing_service_payment -->

                      @if($loan_application->loan_application_filing_service_payment != 0)
                          <!-- Repayment - Service Fee -->
                          <tr>
                            <td align="center">{{ $trans_number }}</td> <?php $trans_number++; ?>
                            <td align="left">{{ date('F j, Y', strtotime($payment_collection->payment_collection_date)) }}</td>
                            <td>Repayment - Service Fee</td>
                            <td align="right">-{{ number_format($payment_collection->payment_collection_service_fee,2) }}</td>
                            <?php $balance_amount -= $payment_collection->payment_collection_service_fee; ?>
                            <td align="right">{{ number_format($balance_amount,2) }}</td>
                          </tr>

                          <!-- Repayment - Filing Fee -->
                          <tr>
                            <td align="center"> {{ $trans_number }} </td> <?php $trans_number++; ?>
                            <td align="left">{{ date('F j, Y', strtotime($payment_collection->payment_collection_date)) }}</td>
                            <td>Repayment - Filing Fee</td>
                            <td align="right">-{{ number_format($payment_collection->payment_collection_filing_fee,2) }}</td>
                            <?php $balance_amount -= $payment_collection->payment_collection_filing_fee; ?>
                            <td align="right">{{ number_format($balance_amount,2) }}</td>
                          </tr>
                      @endif

                        @if($payment_collection->is_paid = 1)
                          <!-- Repayment - Principal -->
                          <tr>
                            <td align="center"> {{ $trans_number }} </td> <?php $trans_number++; ?>
                            <td align="left">{{ date('F j, Y', strtotime($payment_collection->payment_collection_date)) }}</td>
                            <td>Repayment - Principal</td>
                            <td align="right">-{{ number_format($payment_collection->payment_collection_principal_amount ,2) }}</td>
                            <?php
                              $balance_amount -= $payment_collection->payment_collection_principal_amount;
                            ?>
                            <td align="right">{{ number_format($balance_amount) }}</td>
                          </tr>
                        @endif

                        </tbody>
                    @endforeach
                    </tbody>
                  </table>
            </div>
          @endforeach 
         
      </body>
</html>
<?php

use App\LoanApplication;
use App\PaymentSchedule;

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
?>


<!DOCTYPE html5>
<html>
      <head>
            <title>Payment Schedule | Loan Management System</title>

            <style type="text/css">
                #hor-minimalist-b
                  {
                        font-family: "Arial", helvetica, sans-serif;
                        font-size: 12px;
                        background: #fff;
                        margin-left: 35px;
                        margin-right: 35px;
                        margin-top: 5px;
                        width: 100%;
                        border-collapse: collapse;
                        text-align: left;
                  }
                  #hor-minimalist-b th
                  {
                        font-size: 14px;
                        font-weight: normal;
                        color: #039;
                        padding: 10px 8px;
                        border-bottom: 2px solid #6678b1;
                  }
                  #hor-minimalist-b td
                  {
                        border-bottom: 1px solid #ccc;
                        color: #000;
                        padding: 6px 8px;
                  }
                  #hor-minimalist-b tbody tr:hover td
                  {
                        color: #009;
                  }

                  .loaninfo
                  {
                        margin: 35px;
                  }
               
                p, strong, h3
                {
                  font-family: helvetica;
                  font-size: 14px;
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
                    font-family: helvetica;
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
            <p style="text-align: center;">
              <normal style="font-size: 18px">Moo Loans Inc.</normal>
            </p>
          @foreach($loan_applications as $loan_application)
            <?php

                  $cyclesForTheMonth = 0;
                  $previousDate = null;
                  $currentDate = (new DateTime(date('Y-m-d')));
                  $currentMonth = (new DateTime(date('Y-m')));
                  $cyclesPaid = 0;
                  $cyclesDue = 0;
                  $cyclesRemain = 0;
                  $cyclesDueDates = array();
                  $index = 0;

                  foreach ($loan_application->payment_collections as $payment_collection)
                  {
                        $convertedDate = (new DateTime(date('Y-m-d', strtotime($payment_collection->payment_collection_date))));
                        if ($previousDate == null)
                        {
                              $previousDate = $convertedDate;
                        }
                        $convertedMonth = (new DateTime(date('Y-m', strtotime($payment_collection->payment_collection_date))));

                        $index++;

                        if ($payment_collection->is_paid == 1)
                        {
                              $cyclesPaid ++;
                        }
                        //check also if the previous cycle that is in the same month isn't paid yet, if that's the case, do not include the next cycle of the same month to the due cycle (confusing af but it works)
                        else if ( ($convertedDate <= $currentDate) || ( ($convertedMonth == $currentMonth) && !($previousDate < $convertedDate) ) )
                        {
                              $cyclesDueDates[$index]["id"] = $payment_collection->id;
                              $cyclesDueDates[$index]["date"] = $payment_collection->payment_collection_date;
                              $cyclesDueDates[$index]["collection_amount"] = $payment_collection->payment_collection_amount;
                              $cyclesDueDates[$index]["is_paid"] = $payment_collection->is_paid;
                              $cyclesDue ++;
                        }
                        else if (($convertedDate > $currentDate) && ($convertedMonth != $currentMonth || $previousDate < $convertedDate))
                        {
                              $cyclesRemain ++;
                        }

                        //In order to check previous cycle which isn't paid yet.
                        $previousDate = $convertedDate;
                  }

            ?>
            <div class="loaninfo">
                  <p><strong>LOAN INFORMATION</strong></p><hr>
                  <strong>Application ID:</strong> {{ $loan_application->id }}</p>
                  <p><strong>Borrower's Name:</strong> {{ $loan_application->loan_borrower->borrower_first_name }} {{ $loan_application->loan_borrower->borrower_middle_name }} {{ $loan_application->loan_borrower->borrower_last_name }}
                        <br>
                        <strong>Address:</strong> {{ $loan_application->loan_borrower->borrower_home_address }}
                  </p>

                  <p><strong>Cycles Paid:</strong> {{ $cyclesPaid }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Due + To be paid:</strong> {{ $cyclesDue }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Remaining:</strong> {{ $cyclesRemain }}</p> <hr>
            </div>

            <center>
                  <p><strong>Payment Schedule</strong>
                  <br>generated as of {{ $today }}
                  </p>
                  <table id="hor-minimalist-b">
                        <thead>
                              <tr>
                                    <td><strong>Date</strong></td>
                                    <td><strong>Is Paid?</strong></td>
                              </tr>
                        </thead>
                        <tbody>
                              @foreach($loan_application->payment_collections as $collection)
                                    <tr>
                                          <td>{{ date('F j, Y', strtotime($collection->payment_collection_date)) }}</td>
                                          @if($collection->is_paid == 1)
                                                <td>Yes</td>
                                          @elseif($collection->is_paid == 0)
                                                <td>Not yet</td>
                                          @endif
                                    </tr>
                              @endforeach
                        </tbody>
                  </table>
            </center>

            <br>
            <p><i>This report is system generated.</i></p>
          @endforeach 

         
      </body>
</html>
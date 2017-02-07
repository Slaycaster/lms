<?php

use App\LoanApplication;
use App\LoanPayment;

	$timestamp = time()+date("Z");
	$today = gmdate("Y/m/d",$timestamp);
	$company_id = Session::get('company_id', 1);

	$loan_applications = LoanApplication::where('loan_application_status', '=', 'Approved')
            ->where('company_id', '=', $company_id)
            ->with('loan_borrower')
            ->with('loan_borrower.company')
            ->with('loan_interest')
            ->with('loan_payment_term')
            ->with('loan_payments')
            ->get();

    $loan_application_count = LoanApplication::where('loan_application_status', '=', 'Approved')
            ->where('company_id', '=', $company_id)
            ->with('loan_borrower')
            ->with('loan_borrower.company')
            ->with('loan_interest')
            ->with('loan_payment_term')
            ->with('loan_payments')
            ->count();

?>


<!DOCTYPE html5>
<html>
    <head>
        <title>Loan Collection Report | Loan Management System</title>

        <style type="text/css">
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
            p, strong, h3
            {
                font-family: helvetica;
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
            <br>
            <strong>LOAN COLLECTION REPORT <br>as of {{$date}}</strong>
        </p>

        <?php
            $totalAmountCollected = 0;
            $totalPrincipalCollected = 0;
            $totalIncomeCollected = 0;

            $totalAmountOutstanding = 0;
            $totalPrincipalOutstanding = 0;
            $totalIncomeOutstanding = 0;
        ?>

        @foreach($loan_applications as $loan_application)
        <?php
            $monthlyInterest = $loan_application->loan_application_amount * ($loan_application->loan_interest->loan_interest_rate * .01);
            $totalInterest = $monthlyInterest * $loan_application->loan_payment_term->loan_payment_term_no_of_months;
            $totalLoan = $loan_application->loan_application_amount +  $loan_application->loan_application_filing_fee + $loan_application->loan_application_service_fee + ($monthlyInterest * $loan_application->loan_payment_term->loan_payment_term_no_of_months);
            $totalAmount = 0;
            $totalPaymentCount = 0;
            $totalPrincipal = 0;
            $totalIncome = 0;

            $outstandingAmount = 0;
            $outstandingPrincipal = 0;
            $outstandingIncome = 0;


            //Getting how many months already paid
            foreach($key[0]->loan_payments as $loan_payment)
            {
                $totalAmount += (double)$loan_payment->loan_payment_amount;
                $totalPaymentCount += (int)$loan_payment->loan_payment_count;
            }

            $totalPrincipal = ($loan_application->loan_application_amount / $loan_application->loan_payment_term->loan_payment_term_no_of_months) * $totalPaymentCount;
            $totalIncome = $monthlyInterest * $totalPaymentCount;

            $outstandingAmount = $totalLoan - $totalAmount;
            $outstandingPrincipal = ($loan_application->loan_application_amount * $loan_application->loan_payment_term->loan_payment_term_no_of_months) - $totalPrincipal;
            $outstandingIncome = $totalInterest - $totalIncome;

            //Add altogether to the overall
            $totalAmountCollected += $totalAmount;
            $totalPrincipalCollected += $totalPrincipal;
            $totalIncomeCollected += $totalIncome;
            $totalAmountOutstanding += $outstandingAmount;
            $totalPrincipalOutstanding += $outstandingPrincipal;
            $totalIncomeCollected += $outstandingIncome;

        ?>
        @endforeach
        <h2>COLLECTED</h2>
        <table border="1" width="520">
            <thead>
                <tr>
                    <td>Total Amount Collected</td>
                    <td>Total Principal Collected</td>
                    <td>Total Income Collected</td>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td>{{ $totalAmountCollected }}</td>
                    <td>{{ $totalPrincipalCollected }}</td>
                    <td>{{ $totalIncomeCollected }}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <h2>OUTSTANDING</h2>
        <table border="1" width="520">
            <thead>
                <tr>
                    <td>Outstanding Balance</td>
                    <td>Outstanding Principal</td>
                    <td>Outstanding Income Share</td>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td>{{ $totalAmountCollected }}</td>
                    <td>{{ $totalPrincipalCollected }}</td>
                    <td>{{ $totalIncomeCollected }}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <h3>Active Loan Applications: {{$loan_application_count}}</h3>
    </body>
</html>
<?php

use App\LoanApplication;
use App\LoanPayment;
use App\PaymentCollection;
use App\Http\Requests;
use App\Company;

	$timestamp = time()+date("Z");
	$today = gmdate("Y/m/d",$timestamp);
	$company_id = Session::get('company_id', 1);
    $date = Session::get('date');

    $payment_collections = PaymentCollection::where('payment_collection_date', '=', $date)
            ->with('loan_application')
            ->whereHas('loan_application.loan_borrower', function($q) {
                $company_id = Session::get('company_id', 1);
                $q->where('company_id', '=', $company_id);
            })
            ->with('loan_application.loan_interest')
            ->with('loan_application.loan_payment_term')
            ->with('loan_application.loan_payments')
            ->with('loan_application.loan_borrower')
            ->get();

    $payment_collections_count = PaymentCollection::where('payment_collection_date', '=', $date)
            ->whereHas('loan_application.loan_borrower', function($q) {
                $company_id = Session::get('company_id', 1);
                $q->where('company_id', '=', $company_id);
            })
            ->count();
    $company = Company::where('id', '=', $company_id)->first();
?>


<!DOCTYPE html5>
<html>
    <head>
        <title>Loan Collection Report | Loan Management System</title>

        <style type="text/css">
            table
            {
                font-size: 14;
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
        <p style="text-align: center;">
            <normal style="font-size: 18px">Moo Loans Inc.</normal>
            <br>
            <strong>LOAN COLLECTION REPORT <br>as of this cycle, {{$date}}</strong>
            <hr>
            <br>
            <strong>{{ $company->company_name }} ({{ $company->company_code }})</strong>
        </p>

        <?php
            $totalAmountCollectedThisCycle = 0;
            $totalPrincipalCollectedThisCycle = 0;
            $totalIncomeCollectedThisCycle = 0;

            $totalAmountOutstandingThisCycle = 0;
            $totalPrincipalOutstandingThisCycle = 0;
            $totalIncomeOutstandingThisCycle = 0;

            foreach($payment_collections as $payment_collection)
            {
                $payment_collection_count_loan_application = PaymentCollection::where('loan_application_id', '=', $payment_collection->loan_application_id)->count();
                if($payment_collection->is_paid == 1)
                {
                    $totalAmountCollectedThisCycle += $payment_collection->payment_collection_amount;
                    $totalPrincipalCollectedThisCycle += ($payment_collection->loan_application->loan_application_amount / $payment_collection_count_loan_application);
                    $totalIncomeCollectedThisCycle += ( ($payment_collection->loan_application->loan_application_total_amount - $payment_collection->loan_application->loan_application_amount) / $payment_collection_count_loan_application );
                }
                else if($payment_collection->is_paid == 0)
                {
                    $totalAmountOutstandingThisCycle += $payment_collection->payment_collection_amount;
                    $totalPrincipalOutstandingThisCycle += ($payment_collection->loan_application->loan_application_amount / $payment_collection_count_loan_application);
                    $totalIncomeOutstandingThisCycle += ( ($payment_collection->loan_application->loan_application_total_amount - $payment_collection->loan_application->loan_application_amount) / $payment_collection_count_loan_application );
                }
            }
        ?>

        <h3>COLLECTED</h3>
        <table border="1" width="520">
            <thead>
                <tr>
                    <td><strong>Total Amount Collected this Cycle</strong></td>
                    <td><strong>Total Principal Collected this Cycle</strong></td>
                    <td><strong>Total Income Collected this Cycle</strong></td>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td align="right">PHP {{ number_format($totalAmountCollectedThisCycle, 2) }}</td>
                    <td align="right">PHP {{ number_format($totalPrincipalCollectedThisCycle, 2) }}</td>
                    <td align="right">PHP {{ number_format($totalIncomeCollectedThisCycle, 2) }}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <h3>OUTSTANDING</h3>
        <table border="1" width="520">
            <thead>
                <tr>
                    <td>Outstanding Balance this Cycle</td>
                    <td>Outstanding Principal this Cycle</td>
                    <td>Outstanding Income this Cycle</td>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td align="right">PHP {{ number_format($totalAmountOutstandingThisCycle, 2) }}</td>
                    <td align="right">PHP {{ number_format($totalPrincipalOutstandingThisCycle, 2) }}</td>
                    <td align="right">PHP {{ number_format($totalIncomeOutstandingThisCycle, 2) }}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <h3>Active Loan Applications in this Cycle: {{ $payment_collections_count }}</h3>
        <table border="1" width="520">
            <thead>
                <tr>
                    <td><strong>Loan ID</strong></td>
                    <td><strong>Client Name</strong></td>
                    <td><strong>Principal Collected</strong></td>
                    <td><strong>Income Collected</strong></td>
                </tr>
            </thead>
            <tbody>
                @foreach($payment_collections as $payment_collection)
                    <tr>
                        <td>{{ $payment_collection->loan_application->id }}</td>
                        <td>{{ $payment_collection->loan_application->loan_borrower->borrower_last_name }}, {{ $payment_collection->loan_application->loan_borrower->borrower_first_name }} {{ $payment_collection->loan_application->loan_borrower->borrower_middle_name }}</td>
                        <td>PHP {{ number_format($payment_collection->loan_application->loan_application_amount / $payment_collection_count_loan_application,2) }}</td>
                        <td>PHP {{ number_format(($payment_collection->loan_application->loan_application_total_amount - $payment_collection->loan_application->loan_application_amount) / $payment_collection_count_loan_application ,2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
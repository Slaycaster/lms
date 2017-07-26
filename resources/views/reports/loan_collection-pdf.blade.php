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
            ->where('company_id', '=', $company_id)
            ->with('loan_application')
            ->with('loan_application.loan_interest')
            ->with('loan_application.loan_payment_term')
            ->with('loan_application.loan_borrower')
            ->get();

    $payment_collections_count = PaymentCollection::where('payment_collection_date', '=', $date)
            ->whereHas('loan_application.loan_borrower', function($q) {
                $company_id = Session::get('company_id', 1);
                $q->where('company_id', '=', $company_id);
            })
            ->count();

    $company = Company::where('id', '=', $company_id)->first();
    $total_principal_collection = PaymentCollection::where('company_id', '=', $company->id)->sum('payment_collection_principal_amount');
    $total_income_collection = PaymentCollection::where('company_id', '=', $company->id)->sum('payment_collection_interest_amount');
    $total_filing_fee_collection = PaymentCollection::where('company_id', '=', $company->id)->sum('payment_collection_filing_fee');
    $total_service_fee_collection = PaymentCollection::where('company_id', '=', $company->id)->sum('payment_collection_service_fee');
    $total_collection = $total_principal_collection + $total_income_collection + $total_filing_fee_collection + $total_service_fee_collection;
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

    <body onload="window.print();">
        <center><img src="{{ asset('img/mooloans_logo_web.jpg') }}" width="30%" style="position: inherit;"></center>
        <p style="text-align: center;">
            <strong>LOAN COLLECTION REPORT <br>as of this cycle, {{$date}}</strong>
            <hr>
            <br>
            <strong>{{ $company->company_name }} ({{ $company->company_code }})</strong>
        </p>

        <?php
            $totalAmountCollectedThisCycle = 0;
            $totalPrincipalCollectedThisCycle = 0;
            $totalInterestCollectedThisCycle = 0; //Interest
            $totalFilingFeeCollectedThisCycle = 0; //Fees
            $totalServiceFeeCollectedThisCycle = 0; //Fees
            $totalIncomeCollectedThisCycle = 0; //Interest + Fees

            $totalAmountOutstandingThisCycle = 0;
            $totalPrincipalOutstandingThisCycle = 0;
            $totalInterestOutstandingThisCycle = 0; //Interest
            $totalFilingFeeOutstandingThisCycle = 0; //Fees
            $totalServiceFeeOutstandingThisCycle = 0; //Fees
            $totalIncomeOutstandingThisCycle = 0; //Interest + Fees

            foreach($payment_collections as $payment_collection)
            {
                $payment_collection_count_loan_application = PaymentCollection::where('loan_application_id', '=', $payment_collection->loan_application_id)->count();
                if($payment_collection->is_paid == 1)
                {
                    $totalAmountCollectedThisCycle += $payment_collection->payment_collection_principal_amount + $payment_collection->payment_collection_interest_amount + $payment_collection->payment_collection_filing_fee + $payment_collection->payment_collection_service_fee;
                    $totalPrincipalCollectedThisCycle += $payment_collection->payment_collection_principal_amount;
                    $totalInterestCollectedThisCycle += $payment_collection->payment_collection_interest_amount;
                    $totalIncomeCollectedThisCycle += ($payment_collection->payment_collection_interest_amount + $payment_collection->payment_collection_filing_fee + $payment_collection->payment_collection_service_fee);
                    $totalFilingFeeCollectedThisCycle += $payment_collection->payment_collection_filing_fee;
                    $totalServiceFeeCollectedThisCycle += $payment_collection->payment_collection_service_fee;
                }
                else if($payment_collection->is_paid == 0)
                {
                    $totalAmountOutstandingThisCycle += $payment_collection->payment_collection_principal_amount + $payment_collection->payment_collection_interest_amount + $payment_collection->payment_collection_filing_fee + $payment_collection->payment_collection_service_fee;
                    $totalPrincipalOutstandingThisCycle += $payment_collection->payment_collection_principal_amount;
                    $totalInterestOutstandingThisCycle += $payment_collection->payment_collection_interest_amount;
                    $totalIncomeOutstandingThisCycle += ($payment_collection->payment_collection_interest_amount + $payment_collection->payment_collection_filing_fee + $payment_collection->payment_collection_service_fee);
                    $totalFilingFeeOutstandingThisCycle += $payment_collection->payment_collection_filing_fee;
                    $totalServiceFeeOutstandingThisCycle += $payment_collection->payment_collection_service_fee;
                }
            }

            $totalIncomeShareThisCycle = $totalInterestCollectedThisCycle * ($company->company_income_share * 0.01);
            
            $totalOutstandingShareThisCycle = $totalInterestOutstandingThisCycle * ($company->company_income_share * 0.01);
            
            $totalFeesShareThisCycle = ($totalFilingFeeCollectedThisCycle + $totalServiceFeeCollectedThisCycle) * ($company->company_fees_share * 0.01);
            $totalOutstandingFeesShareThisCycle = ($totalFilingFeeOutstandingThisCycle + $totalServiceFeeOutstandingThisCycle) * ($company->company_fees_share * 0.01);
        ?>

        <h3>COLLECTED</h3>
        <table border="1" width="520">
            <thead>
                <tr>
                    <td><strong>Total Amount Collected this Cycle</strong></td>
                    <td><strong>Total Principal Collected this Cycle</strong></td>
                    <td><strong>Total Interest Collected this Cycle</strong></td>
                    <td><strong>Total Fees Collected this Cycle</strong></td>
                    <td><strong>Total Income Collected this Cycle</strong></td>
                    <td><strong>Total Income Share Collected this Cycle ({{ $company->company_income_share }}%)<br>+ Fees Share ({{$company->company_fees_share}}%)</strong></td>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td align="left">PHP {{ number_format($totalAmountCollectedThisCycle, 2) }}</td>
                    <td align="left">PHP {{ number_format($totalPrincipalCollectedThisCycle, 2) }}</td>
                    <td align="left">PHP {{ number_format($totalInterestCollectedThisCycle, 2) }}</td>
                    <td align="left">PHP {{ number_format($totalFilingFeeCollectedThisCycle + $totalServiceFeeCollectedThisCycle, 2) }}</td>
                    <td align="left">PHP {{ number_format($totalIncomeCollectedThisCycle, 2) }}</td>
                    <td align="left">PHP {{ number_format($totalIncomeShareThisCycle + $totalFeesShareThisCycle, 2) }}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <h3>OUTSTANDING</h3>
        <table border="1" width="520">
            <thead>
                <tr>
                    <td><strong>Outstanding Balance this Cycle</strong></td>
                    <td><strong>Outstanding Principal this Cycle</strong></td>
                    <td><strong>Outstanding Interest this Cycle</strong></td>
                    <td><strong>Outstanding Fees this Cycle</strong></td>
                    <td><strong>Outstanding Income this Cycle</strong></td>
                    <td><strong>Outstanding Income Share this Cycle ({{ $company->company_income_share }}%)<br>+ Fees Share ({{$company->company_fees_share}}%)</strong></td>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td align="left">PHP {{ number_format($totalAmountOutstandingThisCycle, 2) }}</td>
                    <td align="left">PHP {{ number_format($totalPrincipalOutstandingThisCycle, 2) }}</td>
                    <td align="left">PHP {{ number_format($totalInterestOutstandingThisCycle, 2) }}</td>
                    <td align="left">PHP {{ number_format($totalFilingFeeOutstandingThisCycle + $totalServiceFeeOutstandingThisCycle, 2) }}</td>
                    <td align="left">PHP {{ number_format($totalIncomeOutstandingThisCycle, 2) }}</td>
                    <td align="left">PHP {{ number_format($totalOutstandingShareThisCycle + $totalOutstandingFeesShareThisCycle, 2) }}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <h3>Total Collection</h3>
        <table border="1" width="520">
            <thead>
                <tr>
                    <td>Total Collection</td>
                    <td>Total Principal</td>
                    <td>Total Interest</td>
                    <td>Total Fees</td>
                    <td>Total Income Share ({{ $company->company_income_share }}%) + Fees Share ({{$company->company_fees_share}}%)</td>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td align="left">PHP {{ number_format($total_collection, 2) }}</td>
                    <td align="left">PHP {{ number_format($total_principal_collection, 2) }}</td>
                    <td align="left">PHP {{ number_format($total_income_collection, 2) }}</td>
                    <td align="left">PHP {{ number_format($total_filing_fee_collection + $total_service_fee_collection, 2) }}</td>
                    <td align="left">PHP {{ number_format($total_income_collection * ($company->company_income_share * 0.01),2) }}</td>
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
                    @if(($payment_collection->payment_collection_principal_amount != 0 || $payment_collection->payment_collection_interest_amount != 0))
                        <tr>
                            <td>{{ $payment_collection->loan_application->id }}</td>
                            <td>{{ $payment_collection->loan_application->loan_borrower->borrower_last_name }}, {{ $payment_collection->loan_application->loan_borrower->borrower_first_name }} {{ $payment_collection->loan_application->loan_borrower->borrower_middle_name }}</td>
                            <td>PHP {{ number_format($payment_collection->payment_collection_principal_amount,2) }}</td>
                            <td>PHP {{ number_format($payment_collection->payment_collection_interest_amount + $payment_collection->payment_collection_filing_fee + $payment_collection->payment_collection_service_fee ,2) }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </body>
</html>
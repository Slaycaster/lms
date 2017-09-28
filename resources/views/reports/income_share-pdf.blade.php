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
        <center><img src="{{ asset('img/mooloans_logo_web.jpg') }}" width="30%" height="30%" style="position: inherit;"></center>
        <p style="text-align: center;">
            <strong>INCOME SHARE REPORT <br>as of this cycle, {{$date}}</strong>
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
            $totalIncomePercentageTax = $totalIncomeShareThisCycle * 0.03;
            $netIncomeShareThisCycle = $totalIncomeShareThisCycle - $totalIncomePercentageTax;
            $totalIncomeWitholdingTax = $netIncomeShareThisCycle * 0.1;
            $netNetIncomeShareThisCycle = $netIncomeShareThisCycle - $totalIncomeWitholdingTax;
            $totalIncomeVAT = $netNetIncomeShareThisCycle * 0.12;
            $netNetNetIncomeShareThisCycle = $$netNetIncomeShareThisCycle - $totalIncomeVAT;
            $totalFeesShareThisCycle = ($totalFilingFeeCollectedThisCycle + $totalServiceFeeCollectedThisCycle) * ($company->company_fees_share * 0.01);
        ?>

        <h3>Income Share on the Actual Amount Collection</h3>
        <table border="1" width="520">
            <thead>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td width="30%">Total Income</td>
                    <td width="20%" align="right"></td>
                    <td width="50%" align="right">{{ number_format($totalIncomeCollectedThisCycle, 2) }}</td>
                </tr>

                <tr>
                    <td width="30%">Total Fees</td>
                    <td width="20%" align="right"></td>
                    <td width="50%" align="right">{{ number_format($totalFilingFeeCollectedThisCycle + $totalServiceFeeCollectedThisCycle, 2) }}</td>
                </tr>

                <tr>
                    <td width="30%">Income Share (+ Fees Share [{{ $company->company_fees_share }}%])</td>
                    <td width="20%" align="right">{{$company->company_income_share}}%</td>
                    <td width="50%" align="right">{{ number_format($totalIncomeShareThisCycle + $totalFeesShareThisCycle, 2) }}</td>
                </tr>

                <tr>
                    <td width="30%"><i>Less: Percentage Tax</i></td>
                    <td width="20%" align="right"><i>3%</i></td>
                    <td width="50%" align="right"><i>({{ number_format($totalIncomePercentageTax, 2) }})</i></td>
                </tr>

                <tr>
                    <td width="30%">Net Income Share</td>
                    <td width="20%" align="right"></td>
                    <td width="50%" align="right">{{ number_format($netIncomeShareThisCycle, 2) }}</td>
                </tr>

                <tr>
                    <td width="30%"><i>Less: Witholding Tax</i></td>
                    <td width="20%" align="right"><i>10%</i></td>
                    <td width="50%" align="right"><i>({{ number_format($totalIncomeWitholdingTax, 2) }})</i></td>
                </tr>

                <tr>
                    <td width="30%">NetNet Income Share</td>
                    <td width="20%" align="right"></td>
                    <td width="50%" align="right">{{ number_format($netNetIncomeShareThisCycle, 2) }}</td>
                </tr>

                <tr>
                    <td width="30%"><i>Less: VAT</i></td>
                    <td width="20%" align="right"><i>12%</i></td>
                    <td width="50%" align="right"><i>({{ number_format($totalIncomeVAT, 2) }})</i></td>
                </tr>

                <tr>
                    <td width="30%">NetNetNet Income Share</td>
                    <td width="20%" align="right"></td>
                    <td width="50%" align="right">{{ number_format($netNetNetIncomeShareThisCycle, 2) }}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <h3>Active Loan Applications in this Cycle: {{ $payment_collections_count }}</h3>
    </body>
</html>
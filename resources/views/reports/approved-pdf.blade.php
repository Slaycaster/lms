<?php

use App\LoanApplication

	$timestamp = time()+date("Z");
	$today = gmdate("Y/m/d",$timestamp);
	$date = Session::get('date', $today);
	$company_id = Session::get('company_id', 1);

	$loan_applications = LoanApplication::where('loan_application_status', '=', 'Approved')
            ->with('loan_borrower')
            ->with('loan_borrower.company')
            ->with('loan_interest')
            ->with('loan_payment_term')
            ->with('loan_payments')
            ->get();

    $loan_application_count = LoanApplication::where('loan_application_status', '=', 'Approved')
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
		<title>Sales Report - {{$date}} | Maria Jose Salon</title>

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
	        <strong>APPROVED LOAN APPLICATION REPORT<br>as of {{$date}}</strong>
	    </p>

	    <table border="1" width="520">
	    	<thead>
	    		<tr>
		    		<td>#</td>
		    		<td>Name of Employee</td>
		    		<td>Company</td>
		    		<td>Date of Application</td>
		    		<td>Interest Rate</td>
		    		<td>Terms of Payment</td>
		    		<td>Disbursement/Date Approved</td>
		    		<td>Principal</td>
		    		<td>Filing Fee</td>
		    		<td>Service Fee</td>
		    		<td>Interest</td>
		    		<td>Total Loan</td>
		    		<td>Monthly Payment</td>
	    		</tr>
	    	</thead>
	    	<tbody>
		    	@foreach($loan_applications as $loan_application)
		    	<?php
		    		$monthlyInterest = $loan_application->loan_application_amount * ($loan_application->loan_interest->loan_interest_rate * .01);
		    		$totalInterest = $monthlyInterest * $loan_application->loan_payment_term->loan_payment_term_no_of_months;
					$totalLoan = $loan_application->loan_application_amount +  $loan_application->loan_application_filing_fee + $loan_application->loan_application_service_fee + ($monthlyInterest * 12);
		    	?>
		    	<tr>
		    		<td>{{ $loan_application->id }}</td>
		    		<td>{{ $loan_application->loan_borrower->borrower_last_name}}, {{ $loan_application->loan_borrower->borrower_first_name }}</td>
		    		<td>{{ $loan_application->loan_borrower->company->company_code }}</td>
		    		<td>{{ $loan_application->created_at }}</td>
		    		<td>{{ $loan_application->loan_interest->loan_interest_rate }}%</td>
		    		<td>{{ $loan_application->loan_payment_term->loan_payment_term_no_of_months }} mos. </td>
		    		<td>{{ $loan_application->updated_at }}</td>
		    		<td>{{ $loan_application->loan_application_amount }}</td>
		    		<td>{{ $loan_application->loan_application_filing_fee }}</td>
		    		<td>{{ $loan_application->loan_application_service_fee }}</td>
		    		<td>{{ round($totalInterest, 2) }}</td>
		    		<td>{{ round($totalLoan, 2) }}</td>
		    		<td>{{ round(($totalLoan / $loan_application->loan_payment_term->loan_payment_term_no_of_months), 2) }}</td>
		    	</tr>
		    	@endforeach
	    	</tbody>
	    </table>
	    <br>
	    <h3>Active Loan Applications: {{$loan_application_count}}</h3>
	</body>
</html>
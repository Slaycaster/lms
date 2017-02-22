<?php

use App\LoanApplication;

	$timestamp = time()+date("Z");
	$today = gmdate("Y/m/d",$timestamp);
	//$date = Session::get('date', $today);
	$application_id = Session::get('application_id', 1);

	$loan_applications = LoanApplication::where('id', '=', $application_id)
            ->with('loan_borrower')
            ->with('comaker1')
            ->with('comaker2')
            ->with('loan_borrower.company')
            ->with('loan_interest')
            ->with('loan_payment_term')
            ->with('loan_payments')
            ->get();
?>

<!DOCTYPE html5>
<html>
	<head>
		<title>Promissory Note | Loan Management System</title>

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
	        <br>
	        <strong>PROMISSORY NOTE<br></strong>
	    </p>
	    <br>
	    @foreach($loan_applications as $loan_application)
	    	<?php
	    		$monthlyInterest = $loan_application->loan_application_amount * ($loan_application->loan_interest->loan_interest_rate * .01);
	    		$totalInterest = $monthlyInterest * $loan_application->loan_payment_term->loan_payment_term_no_of_months;
				$totalLoan = $loan_application->loan_application_amount +  $loan_application->loan_application_filing_fee + $loan_application->loan_application_service_fee + ($monthlyInterest * $loan_application->loan_payment_term->loan_payment_term_no_of_months);
	    	?>

	    	<p><strong>Date:</strong> {{ date('F j, Y', strtotime($today)) }} <br> <strong>Application ID:</strong> {{ $loan_application->id }}</p>
	    	<p><strong>Borrower's Name:</strong> {{ $loan_application->loan_borrower->borrower_first_name }} {{ $loan_application->loan_borrower->borrower_middle_name }} {{ $loan_application->loan_borrower->borrower_last_name }}
	    		<br>
	    		<strong>Address:</strong> {{ $loan_application->loan_borrower->borrower_home_address }}
	    	</p>
	    	<p><strong>Co-Maker's Name:</strong> {{ $loan_application->comaker1->borrower_first_name }} {{ $loan_application->comaker1->borrower_middle_name }} {{ $loan_application->comaker1->borrower_last_name }}
	    		<br>
	    		<strong>Address:</strong> {{ $loan_application->comaker1->borrower_home_address }}
	    	</p>
	    	<p><strong>Co-Maker's Name:</strong> {{ $loan_application->comaker2->borrower_first_name }} {{ $loan_application->comaker2->borrower_middle_name }} {{ $loan_application->comaker2->borrower_last_name }}
	    		<br>
	    		<strong>Address:</strong> {{ $loan_application->comaker2->borrower_home_address }}
	    	</p>
	    	<p>I agree to pay to the order of <strong>Moo Loans, Inc.</strong> ('Lender'), with office address at 5th Floor, Richville Corporate Tower, 1107 Alabang-Zapote Rd., Muntinlupa City the total amount of <strong>PHP {{ $loan_application->loan_application_amount }}</strong>, plus interest thereon at the rate of <strong>{{ $loan_application->loan_interest->loan_interest_rate }} %</strong> per month, together with filing and service fees amounting to <strong>PHP {{ $totalLoan }}</strong> for each installment, to be paid every 15th and 30th of the month, starting on <strong>{{ date('F j, Y', strtotime($loan_application->updated_at)) }}</strong>. Payments shall be first applied to the interest and then the balance to the principal.</p>

	    	<p>It is further agreed that in case my salary cannot meet my outstanding loan obligation, I hereby authorize <strong>{{ $loan_application->loan_borrower->company->company_name }}</strong> to collect from my savings deposit, 13th month pay, sick leave or vacation leave, bonus, termination or separate pay, or any other renumeration or monetary benefit due me. It is furthermore agreed that in case none of the above can meet my outstanding loan obligation my co-maker(s) shall assume said loan obligation.</p>

	    	<p>In case of default in the payment of any interest or part of the principal in this Note, I will also pay to the Lender such amount as will be sufficient to cover the costs and expenses of collecting, including but not limited to reasonable attorney's fees, litigation expenses, and costs of suit.</p>

	    	<p>The above costs and expenses as well as additional interest and penalty equivalent to 10% per month on the outstanding loan obligation will be added to said outstanding loan obligation and will all become immediately due and payable.</p>

	    	<p>The parties hereby indicate by their respective signatures below that they have read and agreed with all the terms and conditions of this Note.</p>

	    	<br><br>

	    	<p><strong>Borrower's Signature:</strong> {{ $loan_application->loan_borrower->borrower_first_name }} {{ $loan_application->loan_borrower->borrower_middle_name }} {{ $loan_application->loan_borrower->borrower_last_name }} <br><strong>Date:</strong></p>
	    	<br>
	    	<p><strong>Co-Maker's Signature:</strong> {{ $loan_application->comaker1->borrower_first_name }} {{ $loan_application->comaker1->borrower_middle_name }} {{ $loan_application->comaker1->borrower_last_name }} <br> <strong>Date:</strong></p>
	    	<br>
	    	<p><strong>Co-Maker's Signature:</strong> {{ $loan_application->comaker2->borrower_first_name }} {{ $loan_application->comaker2->borrower_middle_name }} {{ $loan_application->comaker2->borrower_last_name }} <br> <strong>Date:</strong></p>
	    	<br>
	    	<p><strong>Endorsed by:</strong> <br> <strong>Date:</strong></p>
	    	<br>
	    	<p><strong>Lender's Signature:</strong> <br> <strong>Date:</strong></p>
	    @endforeach 

	   
	</body>
</html>
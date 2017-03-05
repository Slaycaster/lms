@extends('backpack::layout')

@section('header')
	<section class="content-header">
      <h1>
        Loan Payment<small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('backpack.base.route_prefix')) }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">Loan Payment</li>
      </ol>
    </section>
@endsection

@section('content')
	@foreach($loan_application as $key)

	<?php

		$paidForTheMonth = false;
		$current_date = date('M Y');

		$monthsPaid = 0;
		$monthsUnpaid = 0;
		$monthsToBePaid = 0;

		$monthlyInterest = $key[0]->loan_application_amount * ($key[0]->loan_interest->loan_interest_rate * .01);

		$totalLoan = $key[0]->loan_application_amount +  $key[0]->loan_application_filing_fee + $key[0]->loan_application_service_fee + ($monthlyInterest * $key[0]->loan_payment_term->loan_payment_term_no_of_months);

		//Starting Month and Year for your payment since the loan was approved
		$paymentStartDate = (new DateTime(date('Y-m-d', strtotime($key[0]->loan_application_disbursement_date))))->modify('first day of this month');

		//Current Date formatted for DB's datetimestamp
		$currentDate = (new DateTime(date('Y-m-d')))->modify('first day of this month');

		//Ending Month and year for your payment since the loan was approved
		$paymentEndDate = (new DateTime(date('Y-m-d', strtotime($key[0]->loan_application_disbursement_date .'+'. $key[0]->loan_payment_term->loan_payment_term_no_of_months . 'months'))))->modify('first day of this month');

		//1 month payment interval
		$paymentInterval = DateInterval::createFromDateString($key[0]->payment_schedule->payment_schedule_days_interval . ' days');
		$paymentPeriod = new DatePeriod($paymentStartDate, $paymentInterval, $paymentEndDate);
		$paymentPeriodToCurrentDate = new DatePeriod($currentDate, $paymentInterval, $paymentEndDate);

		//Count the payment period from the start to the current date
		$paymentPeriodToCurrentDate_count = count($paymentPeriodToCurrentDate);

		//Getting how many months already paid
		foreach($key[0]->loan_payments as $loan_payment)
		{
			$monthsPaid += (int)$loan_payment->loan_payment_count;
		}

		//Getting how many months having late payments (due)
		$monthsUnpaid = $paymentPeriodToCurrentDate_count - $monthsPaid;
		$monthsToBePaid = $key[0]->loan_payment_term->loan_payment_term_no_of_months - $paymentPeriodToCurrentDate_count;
		

		/*
		$paidForTheMonth = false;
		$current_date = date('M Y');

		$monthsPaid = 0;
		$monthsUnpaid = 0;
		$monthsToBePaid = 0;

		$monthlyInterest = $key[0]->loan_application_amount * ($key[0]->loan_interest->loan_interest_rate * .01);

		$totalLoan = $key[0]->loan_application_amount +  $key[0]->loan_application_filing_fee + $key[0]->loan_application_service_fee + ($monthlyInterest * $key[0]->loan_payment_term->loan_payment_term_no_of_months);

		//Starting Month and Year for your payment since the loan was approved
		$paymentStartDate = (new DateTime(date('Y-m-d', strtotime($key[0]->updated_at))))->modify('first day of this month');

		//Current Date formatted for DB's datetimestamp
		$currentDate = (new DateTime(date('Y-m-d')))->modify('first day of this month');

		//Ending Month and year for your payment since the loan was approved
		$paymentEndDate = (new DateTime(date('Y-m-d', strtotime($key[0]->updated_at .'+'. $key[0]->loan_payment_term->loan_payment_term_no_of_months . 'months'))))->modify('first day of this month');

		//1 month payment interval
		$paymentInterval = DateInterval::createFromDateString('1 month');
		$paymentPeriod = new DatePeriod($paymentStartDate, $paymentInterval, $paymentEndDate);
		$paymentPeriodToCurrentDate = new DatePeriod($currentDate, $paymentInterval, $paymentEndDate);

		//Count the payment period from the start to the current date
		$paymentPeriodToCurrentDate_count = count($paymentPeriodToCurrentDate);

		//Getting how many months already paid
		foreach($key[0]->loan_payments as $loan_payment)
		{
			$monthsPaid += (int)$loan_payment->loan_payment_count;
		}

		//Getting how many months having late payments (due)
		$monthsUnpaid = $paymentPeriodToCurrentDate_count - $monthsPaid;
		$monthsToBePaid = $key[0]->loan_payment_term->loan_payment_term_no_of_months - $paymentPeriodToCurrentDate_count;
		*/
	?>

	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-warning">
				<div class="panel-heading">
					Loan Application Details
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<h3>Application ID:</h3> 
							<p>{{ $key[0]->id }}</p>
						</div>
						<div class="col-md-6">
							<h3>Created at:</h3>
							<p>{{ $key[0]->created_at }}</p>
						</div>
						<div class="col-md-6">
							<h3>Approved at:</h3>
							<p>{{ $key[0]->updated_at }}</p>
						</div>
						<div class="col-md-6">
							<h3>Principal Amount:</h3>
							<p>PHP {{ $key[0]->loan_application_amount }}.00</p>
						</div>
						<div class="col-md-6">
							<h3>Total Loan Amount:</h3>
							<p>PHP {{ $totalLoan }}.00</p>
						</div>
						<div class="col-md-6">
							<h3>Payment Terms:</h3>
							<p>{{ $key[0]->loan_payment_term->loan_payment_term_name }}</p>
						</div>
						<div class="col-md-6">
							<h3>Interest:</h3>
							<p>{{ $key[0]->loan_interest->loan_interest_name }}</p>
						</div>
						<div class="col-md-6">
							<h3>Filing Fee:</h3>
							<p>{{ $key[0]->loan_application_filing_fee }}</p>
						</div>
						<div class="col-md-6">
							<h3>Service Fee:</h3>
							<p>{{ $key[0]->loan_application_service_fee }}</p>
						</div>
						<div class="col-md-12">
							<h3>Purpose:</h3>
							<p>{{ $key[0]->loan_application_purpose }}</p>
						</div>
					</div>
					<hr>
					<h3>Actions</h3>
					<!--=========================================================================
													DUE PAYMENTS
					===========================================================================-->
		              @if($monthsUnpaid > 0)
						<form action="{{url('admin/loan_payments/process_due_payment')}}" method="POST">

						{{ csrf_field() }}
						<input type="hidden" name="loan_application_id" value="{!! $key[0]->id !!}">
						<input type="hidden" name="months_unpaid" value="{!! $monthsUnpaid !!}">

			              <div class="form-group">
			                <label for="due_amount" class="control-label">Pay Due Payments (This Months + {{$monthsUnpaid}} month/s)</label>
			                <div class="input-group">
			                  <span class="input-group-addon">₱</span>
			                  <input type="text" name="due_amount" class="form-control" value="{!! round((($totalLoan / $key[0]->loan_payment_term->loan_payment_term_no_of_months) + (($totalLoan / $key[0]->loan_payment_term->loan_payment_term_no_of_months)*$monthsUnpaid) ), 2) !!}">
			                </div>
			              </div>

			              <!-- Remarks Form Group -->
			              <div class="form-group">
			                <label for="due_remarks" class="control-label">Remarks</label>
			                <textarea name="due_remarks" class="form-control"></textarea>
			              </div>

			              <button type="submit" class="btn btn-block btn-success btn-sm" name="approve">Process Due Loan Payment</button>
						</form>
			          @else
			              @if($paidForTheMonth)
			              		<p class="text-success">Payment already made this month.</p>
			              @else
				              <form action="{{ url('admin/loan_payments/process_payment') }}" method="POST">
				              		
				              	{{ csrf_field() }}
				              	<input type="hidden" name="loan_application_id" value="{!! $key[0]->id !!}">
								  <!-- Amount Form Group -->
					              <div class="form-group">
					                <label for="amount" class="control-label">Monthly Payment</label>
					                <div class="input-group">
					                  <span class="input-group-addon">₱</span>
					                  <input type="text" name="amount" class="form-control" value="{!! round(($totalLoan / $key[0]->loan_payment_term->loan_payment_term_no_of_months), 2) !!}">
					                </div>
					              </div>

					              <!-- Remarks Form Group -->
					              <div class="form-group">
					                <label for="remarks" class="control-label">Remarks</label>
					                <textarea name="remarks" class="form-control"></textarea>
					              </div>

					              <button type="submit" class="btn btn-block btn-success btn-sm" name="approve">Process Loan Payment</button>
					              
				              </form>
			              @endif
		              @endif

		            <!--=========================================================================
												MONTHLY PAYMENT
					===========================================================================-->
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Borrower Details
				</div>
				<div class="panel-body">

						<div class="row">
							
							<div class="col-md-12">
								<h4><strong>{{ $key[0]->loan_borrower->borrower_last_name }}, {{ $key[0]->loan_borrower->borrower_first_name }} {{ $key[0]->loan_borrower->borrower_middle_name }}</strong>
								</h4>
								<p>
									
									<strong>Borrower's Address:</strong> {{ $key[0]->loan_borrower->borrower_home_address }}
									<br>
									<strong>E-mail:</strong> {{ $key[0]->loan_borrower->borrower_email }}
									<br>
									<strong>Civil Status:</strong> {{ $key[0]->loan_borrower->borrower_civil_status }}
									<br>
									<strong>Birth Date:</strong> {{ $key[0]->loan_borrower->borrower_birth_date }}
									<br>
									<strong>Employment Date:</strong> {{ $key[0]->loan_borrower->borrower_employment_date }}
									<br>
									<strong>Assignment Date:</strong> {{ $key[0]->loan_borrower->borrower_assignment_date }}
									<br>
									<strong>Company:</strong> {{ $key[0]->loan_borrower->company->company_name }} ({{ $key[0]->loan_borrower->company->company_code }})
								</p>
							</div>
							<div class="col-md-12">
								<h3>Co-Maker 1:</h3>
								<h4><strong>{{ $key[0]->comaker1->borrower_last_name }}, {{ $key[0]->comaker1->borrower_first_name }} {{ $key[0]->comaker1->borrower_middle_name }}</strong>
								</h4>
								<p>
									
									<strong>Borrower's Address:</strong> {{ $key[0]->comaker1->borrower_home_address }}
									<br>
									<strong>E-mail:</strong> {{ $key[0]->comaker1->borrower_email }}
									<br>
									<strong>Civil Status:</strong> {{ $key[0]->comaker1->borrower_civil_status }}
									<br>
									<strong>Birth Date:</strong> {{ $key[0]->comaker1->borrower_birth_date }}
									<br>
									<strong>Employment Date:</strong> {{ $key[0]->comaker1->borrower_employment_date }}
									<br>
									<strong>Assignment Date:</strong> {{ $key[0]->comaker1->borrower_assignment_date }}
									<br>
									<strong>Company:</strong> {{ $key[0]->comaker1->company->company_name }} ({{ $key[0]->comaker1->company->company_code }})
								</p>
							</div>
							<div class="col-md-12">
								<h3>Co-Maker 2:</h3>
								<h4><strong>{{ $key[0]->comaker2->borrower_last_name }}, {{ $key[0]->comaker2->borrower_first_name }} {{ $key[0]->comaker2->borrower_middle_name }}</strong>
								</h4>
								<p>
									
									<strong>Borrower's Address:</strong> {{ $key[0]->comaker2->borrower_home_address }}
									<br>
									<strong>E-mail:</strong> {{ $key[0]->comaker2->borrower_email }}
									<br>
									<strong>Civil Status:</strong> {{ $key[0]->comaker2->borrower_civil_status }}
									<br>
									<strong>Birth Date:</strong> {{ $key[0]->comaker2->borrower_birth_date }}
									<br>
									<strong>Employment Date:</strong> {{ $key[0]->comaker2->borrower_employment_date }}
									<br>
									<strong>Assignment Date:</strong> {{ $key[0]->comaker2->borrower_assignment_date }}
									<br>
									<strong>Company:</strong> {{ $key[0]->comaker2->company->company_name }} ({{ $key[0]->comaker2->company->company_code }})
								</p>
							</div>
						</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Payment Schedules
				</div>

				<div class="panel-body">
					<table class="table table-bordered">
						<thead>
							<tr>
								<td><strong>Date</strong></td>
								<td><strong>Amount</strong></td>
							</tr>
						</thead>

						<tbody>
							@foreach($key[0]->loan_payments as $payments)
								<tr>
									<td>{{ date('M j, Y - H:i:s', strtotime($payments->created_at)) }}</td>
									<td>{{$payments->loan_payment_amount}}</td>
									<td>{{ $payments->remarks }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>

			<div class="panel panel-success">
				<div class="panel-heading">
					Payment History
				</div>

				<div class="panel-body">
					<div class="row">
						<div class="col-md-4">
							<h3>Months Paid:</h3> 
							<p>{{ $monthsPaid }}</p>
						</div>
						<div class="col-md-4">
							<h3>Due from previous months:</h3>
							<p>{{ $monthsUnpaid }}</p>
						</div>
						<div class="col-md-4">
							<h3>Remaining months:</h3>
							<p>{{ $monthsToBePaid }}</p>
						</div>
					</div>
					<table class="table table-bordered">
						<thead>
							<tr>
								<td><strong>Date</strong></td>
								<td><strong>Amount</strong></td>
								<td><strong>Remarks</strong></td>
							</tr>
						</thead>

						<tbody>
							@foreach($key[0]->loan_payments as $payments)
								<tr>
									<td>{{ date('M j, Y - H:i:s', strtotime($payments->created_at)) }}</td>
									<td>{{$payments->loan_payment_amount}}</td>
									<td>{{ $payments->remarks }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	@endforeach
@endsection
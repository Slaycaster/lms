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
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-warning">
				<div class="panel-heading">
					Loan Application Details
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-6">
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
						<div class="col-md-12">
							<h3>Amount:</h3>
							<p>PHP {{ $key[0]->loan_application_amount }}.00</p>
						</div>
						<div class="col-md-6">
							<h3>Payment Terms:</h3>
							<p>{{ $key[0]->loan_payment_term->loan_payment_term_name }}</p>
						</div>
						<div class="col-md-6">
							<h3>Interest:</h3>
							<p>{{ $key[0]->loan_interest->loan_interest_name }}</p>
						</div>
						<div class="col-md-12">
							<h3>Purpose:</h3>
							<p>{{ $key[0]->loan_application_purpose }}</p>
						</div>
					</div>
					<hr>
					<h3>Actions</h3>

					<?php
						$paidForTheMonth = false;
						$current_date = date('M Y');

						$monthsPaid = 0;
						$monthsUnpaid = 0;
						$monthsToBePaid = 0;

						$monthlyInterest = ($key[0]->loan_application_amount / $key[0]->loan_payment_term->loan_payment_term_no_of_months) * ($key[0]->loan_interest->loan_interest_rate * .01);
 
						//Starting Month and Year for your payment since the loan was approved
						$paymentStartDate = (new DateTime(date('Y-m-d', strtotime($key[0]->updated_at))))->modify('first day of this month');

						//Ending Month and year for your payment since the loan was approved
						$paymentEndDate = (new DateTime(date('Y-m-d', strtotime($key[0]->updated_at.'+'. $key[0]->loan_payment_term->loan_payment_term_no_of_months . 'months'))))->modify('first day of this month');

						$paymentInterval = DateInterval::createFromDateString('1 month');
						$paymentPeriod = new DatePeriod($paymentStartDate, $paymentInterval, $paymentEndDate);

						/*==================================================================
											Checks for balances and dues.
						====================================================================*/

						foreach($paymentPeriod as $dt)
						{
							$payment_period_date = $dt->format('M Y');
							$payment_history_date = new Date();

							foreach($key[0]->loan_payments as $payment_history)
							{
								$payment_history_date = date('M Y', strtotime($payment_history->created_at));
							}

							if (strtotime($payment_period_date) == strtotime($payment_history_date))
							{
								$monthsPaid = $monthsPaid + 1;
								if(strtotime($payment_period_date) == strtotime($current_date))
								{
									$paidForTheMonth = true;			
								}
							}

							else if (strtotime($payment_period_date) >= strtotime($current_date))
							{
								$monthsToBePaid = $monthsToBePaid + 1;
							}

							else if (strtotime($payment_period_date) < strtotime($current_date))
							{
								$monthsUnpaid = $monthsUnpaid + 1;
							}


						}
						//dd($paymentStartDate);
						//dd($paymentInterval);
						//dd($paymentEndDate);
						//echo('Months Paid: '. $monthsPaid . ', Months Unpaid: ' . $monthsUnpaid . ', Months to be Paid: ' . $monthsToBePaid);
						/*
						foreach($key[0]->loan_payments as $payment_history)
						{
							$payment_history_date = date('M Y', strtotime($payment_history->created_at));
							$current_date = date('M Y');
							if($payment_history_date == $current_date)
							{
								$paidForTheMonth = true;
								break;
							} else {
								$paidForTheMonth = false;
							}
						}
						*/
					?>

					<!--=========================================================================
													DUE PAYMENTS
					===========================================================================-->
		              @if($monthsUnpaid > 0)
						<form action="{{url('admin/loan_payments/process_due_payment')}}" method="POST">

						{{ csrf_field() }}
						<input type="hidden" name="loan_application_id" value="{!! $key[0]->id !!}">
						<input type="hidden" name="months_unpaid" value="{!! $monthsUnpaid !!}">

			              <div class="form-group">
			                <label for="due_amount" class="control-label">Pay Due Payments</label>
			                <div class="input-group">
			                  <span class="input-group-addon">₱</span>
			                  <input type="text" name="due_amount" class="form-control" value="{!! ($key[0]->loan_application_amount / $key[0]->loan_payment_term->loan_payment_term_no_of_months) + $monthlyInterest !!}">
			                </div>
			              </div>

			              <!-- Remarks Form Group -->
			              <div class="form-group">
			                <label for="due_remarks" class="control-label">Remarks</label>
			                <textarea name="due_remarks" class="form-control"></textarea>
			              </div>

			              <button type="submit" class="btn btn-block btn-success btn-sm" name="approve">Process Loan Payment</button>
						</form>
			          @else
						<p class="text-success">No due payments.</p>
		              @endif

		            <!--=========================================================================
												MONTHLY PAYMENT
					===========================================================================-->

			              
			              <!-- Remarks Form Group -->
			              <div class="form-group">
			                <label for="remarks" class="control-label">Remarks</label>
			                <textarea name="remarks" class="form-control"></textarea>
			              </div>

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
					                  <input type="text" name="amount" class="form-control" value="{!! ($key[0]->loan_application_amount / $key[0]->loan_payment_term->loan_payment_term_no_of_months) + $monthlyInterest !!}">
					                </div>
					              </div>
					              <button type="submit" class="btn btn-block btn-success btn-sm" name="approve">Process Loan Payment</button>
					              
				              </form>
			              @endif

				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					Payment History
				</div>

				<div class="panel-body">
					<div class="row">
						<div class="col-md-6">
							<h3>Months Paid:</h3> 
							<p>{{ $monthsPaid }}</p>
						</div>
						<div class="col-md-6">
							<h3>Due from previous months:</h3>
							<p>{{ $monthsUnpaid }}</p>
						</div>
						<div class="col-md-6">
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
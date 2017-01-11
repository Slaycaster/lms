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
					<form action="{{url('admin/loan_payments/process_payment')}}" method="POST">
					{{ csrf_field() }}
					<input type="hidden" name="loan_application_id" value="{!! $key[0]->id !!}">

					<?php
						$paidForTheMonth = false;
						/*==================================================================
								Check if already paid for the month. NO ADVANCING
						====================================================================*/

						
						foreach($key[0]->loan_payments as $payment_history)
						{
							$payment_history_date = date('M Y', strtotime($payment_history->created_at));
							$current_date = date('M Y');
							if($payment_history_date == $current_date)
							{
								$paidForTheMonth = true;
							} else {
								$paidForTheMonth = false;
							}
						}
					?>
					<!-- Amount Form Group -->
		              <div class="form-group">
		                <label for="amount" class="control-label">Monthly Payment</label>
		                <div class="input-group">
		                  <span class="input-group-addon">₱</span>
		                  <input type="text" name="amount" class="form-control" value="{!! $key[0]->loan_application_amount / 12 !!}">
		                </div>
		              </div>

		              <!-- Remarks Form Group -->
		              <div class="form-group">
		                <label for="remarks" class="control-label">Remarks</label>
		                <textarea name="remarks" class="form-control"></textarea>
		              </div>
		              @if($paidForTheMonth)
		              		<p class="text-success">Payment already made this month.</p>
		              @else
			              <button type="submit" class="btn btn-block btn-success btn-sm" name="approve">Process Loan Payment</button>
		              @endif

					</form>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					Payment History
				</div>

				<div class="panel-body">
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
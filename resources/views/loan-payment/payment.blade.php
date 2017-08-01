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

		$cyclesForTheMonth = 0;
		$previousDate = null;
		$currentDate = (new DateTime(date('Y-m-d')));
		$currentMonth = (new DateTime(date('Y-m')));
		$cyclesPaid = 0;
		$cyclesDue = 0;
		$cyclesRemain = 0;
		$cyclesDueDates = array();
		$index = 0;
		$terminationFee = $key[0]->loan_application_amount;
		$interestTermination = 0;
		$nextCollectionDate = '';

		foreach ($key[0]->payment_collections as $payment_collection)
		{
			$convertedDate = (new DateTime(date('Y-m-d', strtotime($payment_collection->payment_collection_date))));
			if ($previousDate == null)
			{
				$previousDate = $convertedDate;
			}
			$convertedMonth = (new DateTime(date('Y-m', strtotime($payment_collection->payment_collection_date))));

			if ($payment_collection->is_paid == 1)
			{
				$terminationFee -= $payment_collection->payment_collection_principal_amount;
				$interestTermination = $payment_collection->payment_collection_interest_amount;
				$cyclesPaid ++;
			}
			//check also if the previous cycle that is in the same month isn't paid yet, if that's the case, do not include the next cycle of the same month to the due cycle (confusing af but it works)
			else if ( ($convertedDate <= $currentDate) || ( ($convertedMonth == $currentMonth) && !($previousDate < $convertedDate) ) || ($convertedDate > $currentDate) && ($convertedMonth == $currentMonth) )
			{
				$cyclesDueDates[$index]["id"] = $payment_collection->id;
				$cyclesDueDates[$index]["date"] = $payment_collection->payment_collection_date;
				$cyclesDueDates[$index]["collection_principal_amount"] = $payment_collection->payment_collection_principal_amount;
				$cyclesDueDates[$index]["collection_interest_amount"] = $payment_collection->payment_collection_interest_amount;
				$cyclesDueDates[$index]["collection_filing_fee"] = $payment_collection->payment_collection_filing_fee;
				$cyclesDueDates[$index]["collection_service_fee"] = $payment_collection->payment_collection_service_fee;
				$cyclesDueDates[$index]["is_paid"] = $payment_collection->is_paid;
				$cyclesDue ++;
				$index++;
				$nextCollectionDate = $payment_collection->payment_collection_date;
			}
			else if (($convertedDate > $currentDate) && ($convertedMonth != $currentMonth || $previousDate < $convertedDate))
			{
				$cyclesRemain ++;
			}

			//In order to check previous cycle which isn't paid yet.
			$previousDate = $convertedDate;
		}
	?>

	<div class="row">
		<!-- Loan Payment -->
		<div class="col-md-6">
			<div class="panel panel-warning">
				<div class="panel-heading">
					Loan Payment
				</div>
				<div class="panel-body">
					<h4>Loan Application Details</h4>
					<div class="row">
						<div class="col-md-12">
							<p><strong>Application ID:</strong> {{ $key[0]->id }}</p> 
						</div>
						<div class="col-md-6">
							<p><strong>Created at:</strong> {{ $key[0]->created_at }}</p>
						</div>
						<div class="col-md-6">
							<p><strong>Approved at:</strong> {{ $key[0]->updated_at }}</p>
						</div>
						<div class="col-md-6">
							<p><strong>Principal Amount:</strong> PHP {{ number_format($key[0]->loan_application_amount,2) }}</p>
						</div>
						<div class="col-md-6">
							<p><strong>Total Loan Amount:</strong> PHP {{ number_format($key[0]->loan_application_total_amount,2) }}</p>
						</div>
						<div class="col-md-6">
							<p><strong>Payment Terms:</strong> {{ $key[0]->loan_payment_term->loan_payment_term_name }}</p>
						</div>
						<div class="col-md-6">
							<p><strong>Interest:</strong> {{ $key[0]->loan_interest->loan_interest_name }}</p>
						</div>
						<div class="col-md-6">
							<p><strong>Filing Fee:</strong> PHP {{ number_format($key[0]->loan_application_filing_fee,2) }}</p>
						</div>
						<div class="col-md-6">
							<p><strong>Service Fee:</strong> PHP {{ number_format($key[0]->loan_application_service_fee,2) }}</p>
						</div>
						<div class="col-md-12">
							<p><strong>Purpose:</strong> {{ $key[0]->loan_application_purpose }}</p>
						</div>
					</div>
					<hr>
						<form action="{{ url('admin/loan_payments/process_payment') }}" method="POST">
					    {{ csrf_field() }}
						<h4>Process Payment</h4>
						@if (Session::has('payment-error'))
	              			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	                  		<div class="alert alert-danger alert-dismissable">{{ Session::get('payment-error') }}</div>
	              		@endif
						<table class="table table-hover table-responsive">
							<thead>
								<tr>
									<td><strong>Cycle Date</strong></td>
									<td><strong>Principal</strong></td>
									<td><strong>Interest</strong></td>
									<td><strong>Filing Fee</strong></td>
									<td><strong>Service Fee</strong></td>
									<td><strong>Pay?</strong></td>
								</tr>
							</thead>
							<tbody>
								@foreach($cyclesDueDates as $cyclesDueDate)
									<tr>
										<td>{{$cyclesDueDate["date"]}}</td>
										<td>PHP {{ number_format($cyclesDueDate["collection_principal_amount"], 2) }}</td>
										<td>PHP {{ number_format($cyclesDueDate["collection_interest_amount"], 2) }}</td>
										<td>PHP {{ number_format($cyclesDueDate["collection_filing_fee"], 2) }}</td>
										<td>PHP {{ number_format($cyclesDueDate["collection_service_fee"], 2) }}</td>
										<td>{{ Form::checkbox('payment_collection_id[]', $cyclesDueDate["id"]) }}</td>
									</tr>
								@endforeach
								{{ Form::hidden('application_id', $key[0]->id) }}
							</tbody>
						</table>
						<button type="submit" class="btn btn-block btn-success btn-sm" name="approve">Process Loan Payment</button>
						</form>
					<hr>
						<h4>Process Termination</h4>
						<p><strong>Clicking the button below will terminate the current loan application. Please be careful.</strong></p>
						<p>Termination Fee would be: <strong>PHP {{number_format($terminationFee + $interestTermination,2)}}</strong></p>
						<p>It will be reflected on the next cycle on: <strong>{{ $nextCollectionDate }}</strong></p>
						<button type="submit" data-toggle="modal" data-target="#terminationModal" class="btn btn-block btn-danger btn-sm" name="approve">Process Termination</button>
				</div>
			</div>
		</div> <!-- Loan Payment -->


		<div class="col-md-3">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Borrower Details
				</div>
				<div class="panel-body">

						<div class="row">
							
							<div class="col-md-12">
								<p><strong>{{ $key[0]->loan_borrower->borrower_last_name }}, {{ $key[0]->loan_borrower->borrower_first_name }} {{ $key[0]->loan_borrower->borrower_middle_name }}</strong>
								</p>
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
							<hr>
								<p><strong>Co-Maker 1:</strong> {{ @$key[0]->comaker1->borrower_last_name }}, {{ @$key[0]->comaker1->borrower_first_name }} {{ @$key[0]->comaker1->borrower_middle_name }}</p>
								<p>
									
									<strong>Address:</strong> {{ @$key[0]->comaker1->borrower_home_address }}
									<br>
									<strong>E-mail:</strong> {{ @$key[0]->comaker1->borrower_email }}
									<br>
									<strong>Civil Status:</strong> {{ @$key[0]->comaker1->borrower_civil_status }}
									<br>
									<strong>Birth Date:</strong> {{ @$key[0]->comaker1->borrower_birth_date }}
									<br>
									<strong>Employment Date:</strong> {{ @$key[0]->comaker1->borrower_employment_date }}
									<br>
									<strong>Assignment Date:</strong> {{ @$key[0]->comaker1->borrower_assignment_date }}
									<br>
									<strong>Company:</strong> {{ @$key[0]->comaker1->company->company_name }} ({{ @$key[0]->comaker1->company->company_code }})
								</p>
							</div>
							<div class="col-md-12">
							<hr>
								<p><strong>Co-Maker 2:</strong> {{ @$key[0]->comaker2->borrower_last_name }}, {{ @$key[0]->comaker2->borrower_first_name }} {{ @$key[0]->comaker2->borrower_middle_name }}</p>
								<p>
									
									<strong>Address:</strong> {{ @$key[0]->comaker2->borrower_home_address }}
									<br>
									<strong>E-mail:</strong> {{ @$key[0]->comaker2->borrower_email }}
									<br>
									<strong>Civil Status:</strong> {{ @$key[0]->comaker2->borrower_civil_status }}
									<br>
									<strong>Birth Date:</strong> {{ @$key[0]->comaker2->borrower_birth_date }}
									<br>
									<strong>Employment Date:</strong> {{ @$key[0]->comaker2->borrower_employment_date }}
									<br>
									<strong>Assignment Date:</strong> {{ @$key[0]->comaker2->borrower_assignment_date }}
									<br>
									<strong>Company:</strong> {{ @$key[0]->comaker2->company->company_name }} ({{ @$key[0]->comaker2->company->company_code }})
								</p>
							</div>
						</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Payment Schedules
				</div>

				<div class="panel-body">

					<p><strong>Cycles Paid:</strong> {{ $cyclesPaid }}</p> 
					<p><strong>Due + To be paid:</strong> {{ $cyclesDue }}</p>
					<p><strong>Remaining:</strong> {{ $cyclesRemain }}</p>

					<table class="table table-bordered">
						<thead>
							<tr>
								<td><strong>Date</strong></td>
								<td><strong>Is Paid?</strong></td>
							</tr>
						</thead>
						<tbody>
							@foreach($key[0]->payment_collections as $collection)
								<tr>
									<td>{{ $collection->payment_collection_date }}</td>
									@if($collection->is_paid == 1)
										<td>Yes</td>
									@elseif($collection->is_paid == 0)
										<td>Not yet</td>
									@endif
								</tr>
							@endforeach
						</tbody>
					</table>

				</div>
			</div>
		</div>
	</div><!-- row -->

	<!-- Modal (Pop up when detail button clicked) -->
        <div class="modal fade" id="terminationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" 
                                class="close" 
                                data-dismiss="modal" 
                                aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title"><b>Wait, are you sure?</b></h4>
                    </div>


                     <div class="modal-body">
                        <form action="{{ url('admin/loan_payments/process_termination') }}" method="POST">
					    	{{ csrf_field() }}
					    	{{ Form::hidden('next_due_date', $nextCollectionDate) }}
							{{ Form::hidden('application_id', $key[0]->id) }}
							{{ Form::hidden('termination_fee', $terminationFee) }}
							{{ Form::hidden('interest_termination', $interestTermination) }}
							<p>Termination Fee would be: <strong>PHP {{number_format($terminationFee + $interestTermination,2)}}</strong></p>
							<p>It will be reflected on the next cycle on: <strong>{{ $nextCollectionDate }}</strong></p>
							<p>Are you really sure on terminating this loan application?</p>
					    
                    </div>

                    
                    <div class="modal-footer">
                    	<button type="button"
                                class="btn btn-default btn-sm btn-block"
                                id="btn-dismiss"
                                data-dismiss="modal">
                            Hold it right there!
                        </button>
                        <button type="submit"
                                class="btn btn-danger btn-sm btn-block">
                            Terminate Loan Application
                        </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
	</div>
	@endforeach
@endsection
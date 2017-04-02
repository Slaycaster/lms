@extends('backpack::layout')

@section('header')
	<section class="content-header">
      <h1>
        Loan Application Details<small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('backpack.base.route_prefix')) }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">Loan Application Details</li>
      </ol>
    </section>
@endsection

@section('content')
	@foreach($loan_application as $key)
	<div class="row">
	<div class="col-md-8">
			<div class="panel panel-warning">
				<div class="panel-heading">
					Loan Application Details
				</div>
				<div class="panel-body">
					<a href="{{ url('admin/loan_applications/'.$key->id) }}" class="btn btn-sm btn-primary">Approve/Decline Loan Application</a>
					<hr>
					<div class="row">
						<div class="col-md-6">
							<p><strong>Application ID:</strong> {{ $key->id }}</p>
						</div>
						<div class="col-md-6">
							<p><strong>Created at:</strong> {{ $key->created_at }}</p>
						</div>
						<div class="col-md-6">
							<p><strong>Principal Amount:</strong> PHP {{ $key->loan_application_amount }}</p>
						</div>
						<div class="col-md-6">
							<p><strong>Interest Name - Rate (Total):</strong> {{ $key->loan_interest->loan_interest_name }} - {{ $key->loan_interest->loan_interest_rate }}% ({{ $key->loan_application_interest }})</p>
						</div>
						<div class="col-md-6">
							<p><strong>Payment Terms:</strong> {{ $key->loan_payment_term->loan_payment_term_name }}</p>
						</div>
						<div class="col-md-6">
							<p><strong>Total Amount:</strong> PHP {{ $key->loan_application_total_amount }}</p>
							<p></p>
						</div>
						<div class="col-md-6">
							<p><strong>Periodic Rate:</strong> PHP {{ $key->loan_application_periodic_rate }}</p>
						</div>
						<div class="col-md-12">
							<p><strong>Purpose:</strong> {{ $key->loan_application_purpose }}</p>
						</div>
						<hr>
						<div class="col-md-12">
							<p><strong>Payment Schedules:</strong></p>
							<table class="table table-hover table-responsive" id="payment_scheds" data-page-length='5'>
	                          <thead>
	                            <tr>
	                              <th>Date</th>
	                              <th>Amount</th>
	                            </tr>
	                            <tbody>
	                              @foreach($key->payment_collections as $payment_collection)
	                              	<tr>
	                              		<td>{{ $payment_collection->payment_collection_date }}</td>
	                              		<td>{{ $payment_collection->payment_collection_amount }}</td>
	                              	</tr>
	                              @endforeach
	                            </tbody>
	                          </thead>
	                        </table>
						</div>
					</div>
					<hr>
					
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					Client Details
				</div>
				<div class="panel-body">

						<div class="row">

							<div class="col-md-12">
								<h4><strong>{{ $key->loan_borrower->borrower_last_name }}, {{ $key->loan_borrower->borrower_first_name }} {{ $key->loan_borrower->borrower_middle_name }}</strong>
								</h4>
								<p>

									<strong>Client's Address:</strong> {{ $key->loan_borrower->borrower_home_address }}
									<br>
									<strong>E-mail:</strong> {{ $key->loan_borrower->borrower_email }}
									<br>
									<strong>Civil Status:</strong> {{ $key->loan_borrower->borrower_civil_status }}
									<br>
									<strong>Birth Date:</strong> {{ $key->loan_borrower->borrower_birth_date }}
									<br>
									<strong>Employment Date:</strong> {{ $key->loan_borrower->borrower_employment_date }}
									<br>
									<strong>Assignment Date:</strong> {{ $key->loan_borrower->borrower_assignment_date }}
									<br>
									<strong>Company:</strong> {{ $key->loan_borrower->company->company_name }} ({{ $key->loan_borrower->company->company_code }})
								</p>
							</div>
							<hr>
							<div class="col-md-12">
								<h5>Co-Maker 1:</h5>
								<h4><strong>{{ $key->comaker1->borrower_last_name }}, {{ $key->comaker1->borrower_first_name }} {{ $key->comaker1->borrower_middle_name }}</strong>
								</h4>
								<p>

									<strong>Client's Address:</strong> {{ $key->comaker1->borrower_home_address }}
									<br>
									<strong>E-mail:</strong> {{ $key->comaker1->borrower_email }}
									<br>
									<strong>Civil Status:</strong> {{ $key->comaker1->borrower_civil_status }}
									<br>
									<strong>Birth Date:</strong> {{ $key->comaker1->borrower_birth_date }}
									<br>
									<strong>Employment Date:</strong> {{ $key->comaker1->borrower_employment_date }}
									<br>
									<strong>Assignment Date:</strong> {{ $key->comaker1->borrower_assignment_date }}
									<br>
									<strong>Company:</strong> {{ $key->comaker1->company->company_name }} ({{ $key->comaker1->company->company_code }})
								</p>
							</div>
							<hr>
							<div class="col-md-12">
								<h5>Co-Maker 2:</h5>
								<h4><strong>{{ $key->comaker2->borrower_last_name }}, {{ $key->comaker2->borrower_first_name }} {{ $key->comaker2->borrower_middle_name }}</strong>
								</h4>
								<p>

									<strong>Client's Address:</strong> {{ $key->comaker2->borrower_home_address }}
									<br>
									<strong>E-mail:</strong> {{ $key->comaker2->borrower_email }}
									<br>
									<strong>Civil Status:</strong> {{ $key->comaker2->borrower_civil_status }}
									<br>
									<strong>Birth Date:</strong> {{ $key->comaker2->borrower_birth_date }}
									<br>
									<strong>Employment Date:</strong> {{ $key->comaker2->borrower_employment_date }}
									<br>
									<strong>Assignment Date:</strong> {{ $key->comaker2->borrower_assignment_date }}
									<br>
									<strong>Company:</strong> {{ $key->comaker2->company->company_name }} ({{ $key->comaker2->company->company_code }})
								</p>
							</div>
						</div>
				</div>
			</div>
		</div>

		@endforeach
	</div>
	@section('after_scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			var table = $('#payment_scheds').DataTable();
		});
	</script>
	@endsection
@endsection

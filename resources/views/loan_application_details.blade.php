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
					<form action="{{url('admin/loan_applications/process_application')}}" method="POST">
					{{ csrf_field() }}
					<input type="hidden" name="loan_application_id" value="{!! $key[0]->id !!}">
					<!-- Amount Form Group -->
		              <div class="form-group">
		                <label for="amount" class="control-label">Change Loan Amount</label>
		                <div class="input-group">
		                  <span class="input-group-addon">₱</span>
		                  <input type="text" name="amount" class="form-control" value="{!! $key[0]->loan_application_amount !!}">
		                  <span class="input-group-addon">.00</span>
		                </div>
		              </div>

		              <div class="form-group">
		                <label for="disbursement_date" class="control-label">Disbursement Date</label>
		                <div class="input-group">
		                  <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
		                  <input type="text" name="disbursement_date" class="form-control" placeholder="yyyy-mm-dd">
		                </div>
		              </div>

		              <!-- Remarks Form Group -->
		              <div class="form-group">
		                <label for="remarks" class="control-label">Remarks</label>
		                <textarea name="remarks" class="form-control"></textarea>
		              </div>

		              <button type="submit" class="btn btn-block btn-success btn-sm" name="approve">Approve Loan Application</button>
		              <button type="submit" class="btn btn-block btn-warning btn-sm" name="decline">Decline Loan Application</button>

					</form>
				</div>
			</div>
		</div>
		@endforeach
	</div>
@endsection
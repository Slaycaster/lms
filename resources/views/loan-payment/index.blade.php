@extends('backpack::layout')

@section('header')
	<section class="content-header">
      <h1>
        Loan Payments<small>  Process payments and/or terminations</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('backpack.base.route_prefix')) }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">Loan Payments</li>
      </ol>
    </section>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Approved Loan Applications
				</div>

				<div class="panel-body">
					@if (Session::has('message'))
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  		<div class="alert alert-success alert-dismissable">{{ Session::get('message') }}</div>
              		@endif
              		@if (Session::has('payment_status'))
              			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  		<div class="alert alert-info alert-dismissable">{{ Session::get('payment_status') }}</div>
              		@endif
              		<div class="table-responsive">
              			<table class="table table-bordered" id="users-table">
				            <thead>
				              <tr>
				                <th>ID</th>
				              	<th>Status</th>
				              	<th>Disbursement Date</th>
				                <th>Last Name</th>
				                <th>First Name</th>
				                <th>Company Code</th>
				                <th>Principal Amount</th>
				                <th>Total Loan Amount</th>
				                <th>Payment Terms</th>
				                <th>Actions</th>
				              </tr>
				        	</thead>
			        	</table>	
              		</div>
				</div>
			</div>
		</div>
	</div>

	@section('after_scripts')
		<script type="text/javascript">
		    $(document).ready(function() {
		      $('#users-table').DataTable({
		        processing: true,
		        serverSide: true,
		        responsive: true,
		        ajax: '{!! url('loan_payments/applications') !!}',
		        columns: [
		          {data: 'id', name: 'loan_applications.id'},
		          {data: 'loan_application_status', name: 'loan_applications.loan_application_status'},
		          {data: 'loan_application_disbursement_date', name: 'loan_applications.loan_application_disbursement_date'},
		          {data: 'loan_borrower.borrower_last_name', name: 'loan_borrower.borrower_last_name'},
		          {data: 'loan_borrower.borrower_first_name', name: 'loan_borrower.borrower_first_name'},
		          {data: 'loan_borrower.company.company_code', name: 'loan_borrower.company.company_name'},
		          {data: 'loan_application_amount', name: 'loan_applications.loan_application_amount'},
		          {data: 'loan_application_total_amount', name: 'loan_applications.loan_application_total_amount'},
		          {data: 'loan_payment_term.loan_payment_term_name', name: 'loan_payment_term.loan_payment_term_name'},
		          {data: 'Actions', name: 'Actions', orderable: false, searchable: false}
		        ]
		      });
		    });
		</script>
	@endsection
@endsection
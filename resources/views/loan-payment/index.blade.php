@extends('backpack::layout')

@section('header')
	<section class="content-header">
      <h1>
        Loan Payments<small></small>
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

	@section('after_scripts')
		<script type="text/javascript">
		    $(document).ready(function() {
		      $('#users-table').DataTable({
		        processing: true,
		        serverSide: true,
		        ajax: '{!! url('loan_payments/applications') !!}',
		        columns: [
		          {data: '0', name: 'loan_applications.id'},
		          {data: '7', name: 'loan_applications.loan_application_status'},
		          {data: '11', name: 'loan_applications.loan_application_disbursement_date'},
		          {data: '22.borrower_last_name', name: 'loan_borrower.borrower_last_name'},
		          {data: '22.borrower_first_name', name: 'loan_borrower.borrower_first_name'},
		          {data: '22.company.company_code', name: 'loan_borrower.company.company_name'},
		          {data: '2', name: 'loan_applications.loan_application_amount'},
		          {data: '3', name: 'loan_applications.loan_application_total_amount'},
		          {data: '21.loan_payment_term_name', name: 'loan_payment_term.payment_term_name'},
		          {data: '24', name: 'Actions', orderable: false, searchable: false}
		        ]
		      });
		    });
		</script>
	@endsection
@endsection
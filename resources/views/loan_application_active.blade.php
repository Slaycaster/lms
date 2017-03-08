@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        Active Loan Applications<small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('backpack.base.route_prefix')) }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">Active Loan Applications</li>
      </ol>
    </section>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Current Active Loan Applications
				</div>

				<div class="panel-body">
					<table class="table table-bordered" id="users-table">
			            <thead>
			              <tr>
			              	<th>Status</th>
			                <th>Last Name</th>
			                <th>First Name</th>
			                <th>Company</th>
			                <th>Amount</th>
			                <th>Payment Terms</th>
			                <th>Interest</th>
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
		        ajax: '{!! url('loan_applications/active/data') !!}',
		        columns: [
		          {data: '3', name: 'loan_applications.loan_application_status'},
		          {data: '16.borrower_last_name', name: 'loan_borrower.borrower_last_name'},
		          {data: '16.borrower_first_name', name: 'loan_borrower.borrower_first_name'},
		          {data: '16.company.company_code', name: 'loan_borrower.company.company_name'},
		          {data: '1', name: 'loan_applications.loan_application_amount'},
		          {data: '18.loan_payment_term_name', name: 'loan_payment_term.payment_term_name'},
		          {data: '17.loan_interest_name', name: 'loan_interest.loan_interest_name'},
		          {data: '19', name: 'Actions', orderable: false, searchable: false}
		        ]
		      });
		    });
		</script>
	@endsection
@endsection

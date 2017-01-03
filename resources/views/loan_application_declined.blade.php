@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        Declined Loan Applications<small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('backpack.base.route_prefix')) }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">Declined Loan Applications</li>
      </ol>
    </section>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Current Declined Loan Applications
				</div>

				<div class="panel-body">
					<table class="table table-bordered" id="users-table">
			            <thead>
			              <tr>
			                <th>Last Name</th>
			                <th>First Name</th>
			                <th>Company</th>
			                <th>Amount</th>
			                <th>Payment Terms</th>
			                <th>Interest</th>
			                <th>Remarks</th>
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
		        ajax: '{!! url('loan_applications/declined/data') !!}',
		        columns: [
		          {data: '13.borrower_last_name', name: 'loan_borrower.borrower_last_name'},
		          {data: '13.borrower_first_name', name: 'loan_borrower.borrower_first_name'},
		          {data: '13.company.company_code', name: 'loan_borrower.company.company_name'},
		          {data: '0', name: 'loan_applications.loan_application_amount'},
		          {data: '15.loan_payment_term_name', name: 'loan_payment_term.payment_term_name'},
		          {data: '14.loan_interest_name', name: 'loan_interest.loan_interest_name'},
		          {data: '5', name: 'loan_applications.loan_application_remarks'},
		          {data: '16', name: 'Actions', orderable: false, searchable: false}
		        ]
		      });
		    });
		</script>
	@endsection
@endsection

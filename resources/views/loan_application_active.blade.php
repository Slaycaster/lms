
@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        Approve/Decline Loan Applications<small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('backpack.base.route_prefix')) }}">{{ config('backpack.base.project_name') }}</a></li>
        <li><a href="#">Loan Applications</a></li>
        <li class="active">* Approve/Decline</li>
      </ol>
    </section>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					All Pending/Declined Loan Applications
				</div>

				<div class="panel-body">
					<table class="table table-bordered" id="users-table">
			            <thead>
			              <tr>
			              	<th>ID</th>
			              	<th>Status</th>
			              	<th>Disbursement Date</th>
			                <th>Last Name</th>
			                <th>First Name</th>
			                <th>Company Code</th>
			                <th>Interest Rate</th>
			                <th>Principal Amount</th>
			                <th>Interest</th>
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
		        ajax: '{!! url('loan_applications/active/data') !!}',
		        columns: [
		          {data: '0', name: 'loan_applications.id'},
		          {data: '23', name: 'Actions', orderable: false, searchable: false}
		          {data: '7', name: 'loan_applications.loan_application_status'},
		          {data: '11', name: 'loan_applications.loan_application_disbursement_date'},
		          {data: '20.borrower_last_name', name: 'loan_borrower.borrower_last_name'},
		          {data: '20.borrower_first_name', name: 'loan_borrower.borrower_first_name'},
		          {data: '20.company.company_code', name: 'loan_borrower.company.company_name'},
		          {data: '21.loan_interest_name', name: 'loan_interest.loan_interest_name'},
		          {data: '2', name: 'loan_applications.loan_application_amount'},
		          {data: '4', name: 'loan_applications.loan_application_interest'},
		          {data: '3', name: 'loan_applications.loan_application_total_amount'},
		          {data: '22.loan_payment_term_name', name: 'loan_payment_term.payment_term_name'},
		        ]
		      });
		    });
		</script>
	@endsection
@endsection

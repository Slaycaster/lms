
@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        Approve/Decline Loan Applications<small>  Approve or decline Loan Applications or change its details.</small>
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
					<div class="table-responsive">					
						<table class="table table-bordered table-hover" id="users-table">
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
	</div>
	@section('after_scripts')
		<script type="text/javascript">
		    $(document).ready(function() {
		      $('#users-table').DataTable({
		        processing: true,
		        serverSide: true,
		        ajax: '{!! url('loan_applications/active/data') !!}',
		        columns: [
		          {data: 'id', name: 'loan_applications.id'},
		          {data: 'loan_application_status', name: 'loan_applications.loan_application_status'},
		          {data: 'loan_application_disbursement_date', name: 'loan_applications.loan_application_disbursement_date'},
		          {data: 'loan_borrower.borrower_last_name', name: 'loan_borrower.borrower_last_name'},
		          {data: 'loan_borrower.borrower_first_name', name: 'loan_borrower.borrower_first_name'},
		          {data: 'loan_borrower.company.company_code', name: 'loan_borrower.company.company_name'},
		          {data: 'loan_interest.loan_interest_name', name: 'loan_interest.loan_interest_name'},
		          {data: 'loan_application_amount', name: 'loan_applications.loan_application_amount'},
		          {data: 'loan_application_interest', name: 'loan_applications.loan_application_interest'},
		          {data: 'loan_application_total_amount', name: 'loan_applications.loan_application_total_amount'},
		          {data: 'loan_payment_term.loan_payment_term_name', name: 'loan_payment_term.loan_payment_term_name'},
		          {data: 'Actions', name: 'Actions', orderable: false, searchable: false}
		        ]
		      });
		    });
		</script>
	@endsection
@endsection

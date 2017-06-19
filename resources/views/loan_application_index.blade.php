@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        All Loan Applications<small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('backpack.base.route_prefix')) }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active"><a href="#">Loan Applications</a></li>
        <li>* Loan Applications</li>
      </ol>
    </section>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					All Active Loan Applications
				</div>
				<div class="panel-body">
					@if (Session::has('message'))
                  		<div class="alert alert-info">{{ Session::get('message') }}</div>
              		@endif
					<a href="{{ url('admin/loan_applications/create') }}" class="btn btn-primary btn-sm">+ New Loan Application</a>
					<br><br>
					<div class="table-responsive">
						<table class="table table-bordered table-hover" id="users-table" data-page-length='10'>
				            <thead>
				              <tr>
				              	<th>ID</th>
				              	<th>View</th>
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
		        ajax: '{!! url('loan_applications/index/data') !!}',
		        columns: [
		          {data: 'id', name: 'loan_applications.id', searchable: false},
		          {data: 'Actions', name: 'View', orderable: false, searchable: false},
		          {data: 'loan_application_status', name: 'loan_applications.loan_application_status', searchable: false},
		          {data: 'loan_application_disbursement_date', name: 'loan_applications.loan_application_disbursement_date', orderable: false},
		          {data: 'loan_borrower.borrower_last_name', name: 'loan_borrower.borrower_last_name', searchable: true},
		          {data: 'loan_borrower.borrower_first_name', name: 'loan_borrower.borrower_first_name', searchable: true},
		          {data: 'loan_borrower.company.company_code', name: 'loan_borrower.company.company_name', searchable: false},
		          {data: 'loan_interest.loan_interest_name', name: 'loan_interest.loan_interest_name', searchable: false},
		          {data: 'loan_application_amount', name: 'loan_applications.loan_application_amount', searchable: false},
		          {data: 'loan_application_interest', name: 'loan_applications.loan_application_interest', searchable: false},
		          {data: 'loan_application_total_amount', name: 'loan_applications.loan_application_total_amount', searchable: false},
		          {data: 'loan_payment_term.loan_payment_term_name', name: 'loan_payment_term.loan_payment_term_name', searchable: false}
		        ]
		      });
		    });
		</script>
	@endsection
@endsection

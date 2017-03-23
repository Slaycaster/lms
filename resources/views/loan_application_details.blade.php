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
					<div class="row">
						<div class="col-md-6">
							<h3>Application ID:</h3>
							<p>{{ $key->id }}</p>
						</div>
						<div class="col-md-6">
							<h3>Created at:</h3>
							<p>{{ $key->created_at }}</p>
						</div>
						<div class="col-md-6">
							<h3>Principal Amount:</h3>
							<p>PHP {{ $key->loan_application_amount }}</p>
						</div>
						<div class="col-md-6">
							<h3>Interest Name - Rate (Total):</h3>
							<p>{{ $key->loan_interest->loan_interest_name }} - {{ $key->loan_interest->loan_interest_rate }}% ({{ $key->loan_application_interest }})</p>
						</div>
						<div class="col-md-6">
							<h3>Payment Terms:</h3>
							<p>{{ $key->loan_payment_term->loan_payment_term_name }}</p>
						</div>
						<div class="col-md-6">
							<h3>Total Amount:</h3>
							<p>PHP {{ $key->loan_application_total_amount }}</p>
						</div>
						<div class="col-md-6">
							<h3>Periodic Rate:</h3>
							<p>PHP {{ $key->loan_application_periodic_rate }}</p>
						</div>
						<div class="col-md-12">
							<h3>Purpose:</h3>
							<p>{{ $key->loan_application_purpose }}</p>
						</div>
						<div class="col-md-12">
							<h3>Payment Schedules:</h3>
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
					<h3>Approve/Decline</h3>
					<form action="{{url('admin/loan_applications/process_application')}}" method="POST">
					{{ csrf_field() }}
					<input type="hidden" name="loan_application_id" value="{!! $key->id !!}">

					<!-- Remarks Form Group -->
					<div class="form-group">
						<label for="remarks" class="control-label">Remarks</label>
						<textarea name="remarks" class="form-control"></textarea>
					</div>

					<!--Submit buttons -->
					<button type="submit" class="btn btn-block btn-success btn-sm" name="approve">Approve Loan Application</button>
					<button type="submit" class="btn btn-block btn-warning btn-sm" name="decline">Decline Loan Application</button>
					<hr/>
					<h3>Change Application Form Details</h3>
					<!-- CHANGE/EDIT FORM -->
					<!-- Amount Form Group -->
					<div class="form-group">
						<label for="change">
							Change Details?
						</label>
						{{ Form::checkbox('change_details', 'yes') }}
						<p class="text-muted">Note: Tick the box to ensure that changed details below will be updated, otherwise it will be ignored.</p>
					</div>
					<hr>
					<!-- Co-Maker Form Group -->
					<div class="form-group">
							<label for="borrower">
								Change Co-Maker 1
							</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-address-book"></i></span>
								<div class="row">
									<div class="col-md-3">
										<input type="text" id="comaker1"/>
									</div>
									<div class="col-md-2">
										<p><strong>Co-Maker 1 ID: </strong><span id="comaker1-id"></span></p>
										<input type="hidden" name="comaker1_id" id="comaker1_id" value="{!! $key->loan_application_comaker_id1 !!}">
										<input type="hidden" name="comaker1_text" id="comaker1_text" value="{!! $key->comaker1->borrower_last_name !!}">
									</div>
									<div class="col-md-3">
										<p><strong>Selected Co-Maker 1: </strong><span id="comaker1-txt"></span></p>
									</div>
								</div>
							</div>
					</div>

					<!-- Co-Maker Form Group -->
					<div class="form-group">
							<label for="borrower">
								Change Co-Maker 2
							</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-address-book"></i></span>
								<div class="row">
									<div class="col-md-3">
										<input type="text" id="comaker2"/>
									</div>
									<div class="col-md-2">
										<p><strong>Co-Maker 2 ID: </strong><span id="comaker2-id"></span></p>
										<input type="hidden" name="comaker2_id" id="comaker2_id" value="{!! $key->loan_application_comaker_id2 !!}">
										<input type="hidden" name="comaker2_text" id="comaker2_text" value="{!! $key->comaker2->borrower_last_name !!}">
									</div>
									<div class="col-md-3">
										<p><strong>Selected Co-Maker 2: </strong><span id="comaker2-txt"></span></p>
									</div>
								</div>
							</div>
					</div>

          <div class="form-group">
            <label for="amount" class="control-label">Change Principal Loan Amount</label>
            <div class="input-group">
              <span class="input-group-addon">₱</span>
              <input type="text" id="amount" name="amount" class="form-control" value="{!! $key->loan_application_amount !!}">
            </div>
          </div>

          <div class="form-group">
            <label for="disbursement_date" class="control-label">Change Disbursement Date</label>
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i> </span>
              <input type="text" id="disbursement_date" name="disbursement_date" class="form-control datepicker" placeholder="yyyy-mm-dd" value="{!! $key->loan_application_disbursement_date !!}">
            </div>
          </div>

					<!-- Payment Terms x Loan Interest Row Group -->
					<div class="row">
						<div class="col-md-3 col-sm-5">
								<!-- Payment Terms Form Group -->
								<div class="form-group">
									<label for="payment_term_id" class="control-label">Change Payment Terms</label>
									<div class="input-group">
										{{ Form::select('payment_term_id', $payment_terms, $key->payment_term_id, ['class' => 'form-control', 'id' => 'payment_term_id']) }}
									</div>
								</div>
						</div>

						<div class="col-md-3 col-sm-5">
								<!-- Loan Interest Form Group -->
								<div class="form-group">
									<label for="loan_interest_id" class="control-label">Change Loan Interest</label>
									<div class="input-group">
										{{ Form::select('loan_interest_id', $loan_interests, $key->loan_interest_id, ['class' => 'form-control', 'id' => 'loan_interest_id']) }}
									</div>
								</div>
						</div>

						<div class="col-md-3 col-sm-5">
								<!-- Loan Interest Form Group -->
								<div class="form-group">
									<label for="payment_schedule_id" class="control-label">Change Payment Schedule</label>
									<div class="input-group">
										{{ Form::select('payment_schedule_id', $payment_schedules, $key->payment_schedule_id, ['class' => 'form-control', 'id' => 'payment_schedule_id']) }}
									</div>
								</div>
						</div>
					</div>

					<!-- Filing Fees x Service Fee Row Group -->
					<div class="row">

						<!-- Filing Fees Form Group -->
						<div class="col-md-6 col-sm-10">
							<div class="form-group">
								<label for="filing_fee" class="control-label">Change Filing Fee</label>
								<div class="input-group">
									<span class="input-group-addon">₱</span>
									<input type="text" id="filing_fee" name="filing_fee" class="form-control" autocomplete="off" value="{!! $key->loan_application_filing_fee !!}">
									<span class="input-group-addon">.00</span>
								</div>
							</div>
						</div>

						<div class="col-md-6 col-sm-10">
							<!-- Service Fee Form Group -->
							<div class="form-group">
								<label for="service_fee" class="control-label">Change Service Fee</label>
								<div class="input-group">
									<span class="input-group-addon">₱</span>
									<input type="text" id="service_fee" name="service_fee" class="form-control" autocomplete="off" value="{!! $key->loan_application_service_fee !!}">
									<span class="input-group-addon">.00</span>
								</div>
							</div>
						</div>

						<div class="col-md-3 col-sm-7">
							<!-- Computation Form Group -->
							<div class="form-group">
								<label for="pre_compute" class="control-label">Total Computations and Scheduling </label>
								<a href="#" id="computeBtn" data-toggle="modal" data-target="#scheduleModal" class="btn btn-info btn-sm form-control">Pre-compute</a>
							</div>
						</div>

					</div>

					</form>
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
							<div class="col-md-12">
								<h3>Co-Maker 1:</h3>
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
							<div class="col-md-12">
								<h3>Co-Maker 2:</h3>
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

		<!-- Modal (Pop up when detail button clicked) -->
		<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
						<div class="modal-content">
								<div class="modal-header">
										<button type="button"
														class="close"
														data-dismiss="modal"
														aria-label="Close">
												<span aria-hidden="true">×</span>
										</button>
										<i class="fa fa-circle-o-notch fa-4x"></i>
										<h4 class="modal-title" id="myModalLabel"><b>Pre-computed Payment Schedule</b></h4>
								</div>


								 <div class="modal-body">
										<div class="pre_results">

										</div>
										<table class="table table-hover table-responsive" id="pre_payment_scheds" data-page-length='5'>
											<thead>
												<tr>
													<th>Date</th>
													<th>Amount</th>
												</tr>
												<tbody>

												</tbody>
											</thead>
										</table>
								</div>


								<div class="modal-footer">
										<button type="button"
														class="btn btn-success btn-sm btn-block"
														id="btn-dismiss"
														data-dismiss="modal">
												Okay, got it!
										</button>
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
				$("#comaker1-id").html(document.getElementById('comaker1_id').value);
				$("#comaker2-id").html(document.getElementById('comaker2_id').value);
				$("#comaker1-txt").html(document.getElementById('comaker1_text').value);
				$("#comaker2-txt").html(document.getElementById('comaker2_text').value);
			});
		</script>

		<script type="text/javascript">
		    $('.datepicker').datepicker({
		        format: 'yyyy-mm-dd',
		        startDate: '-3d'
		    });
	  	</script>

			<script>
	         $(document).ready(function () {

	             var comaker1 = $("#comaker1").tautocomplete({
	                 width: "600px",
	                 columns: ['Last Name', 'First Name', 'Middle Name', 'Company'],
	                 placeholder: "Search for Co-Maker 1 by Last Name",
	                 norecord: "No Records Found",
	                 highlight: "",
	                 hide: [false],
	                 ajax: {
	                     url: '{!! url('/borrowers_data') !!}',
	                     type: "GET",
	                     data: function(){var x = { para1: comaker1.searchdata()}; return x;},
	                     success: function (data) {

	                         var filterData = [];

	                         var searchData = eval("/" + comaker1.searchdata() + "/gi");

	                         $.each(data, function (i, v) {
	                             if (v.borrower_last_name.search(new RegExp(searchData)) != -1) {
	                                 filterData.push(v);
	                             }
	                         });
	                         return filterData;
	                     }
	                 },
	                 onchange: function () {
	                     $("#comaker1-txt").html(comaker1.text());
	                     $("#comaker1-id").html(comaker1.id());
	                     document.getElementById('comaker1_id').value = comaker1.id();
	                 }
	             });

	             var comaker2 = $("#comaker2").tautocomplete({
	                 width: "600px",
	                 columns: ['Last Name', 'First Name', 'Middle Name', 'Company'],
	                 placeholder: "Search for Co-Maker 2 by Last Name",
	                 norecord: "No Records Found",
	                 highlight: "",
	                 hide: [false],
	                 ajax: {
	                     url: '{!! url('/borrowers_data') !!}',
	                     type: "GET",
	                     data: function(){var x = { para1: comaker2.searchdata()}; return x;},
	                     success: function (data) {

	                         var filterData = [];

	                         var searchData = eval("/" + comaker2.searchdata() + "/gi");

	                         $.each(data, function (i, v) {
	                             if (v.borrower_last_name.search(new RegExp(searchData)) != -1) {
	                                 filterData.push(v);
	                             }
	                         });
	                         return filterData;
	                     }
	                 },
	                 onchange: function () {
	                     $("#comaker2-txt").html(comaker2.text());
	                     $("#comaker2-id").html(comaker2.id());
	                     document.getElementById('comaker2_id').value = comaker2.id();
	                 }
	             });
	         });
	       </script>

				 <script type="text/javascript">
			     $("#computeBtn").click(function(){
			         $.ajax({
			             type: "POST",
			             url: '{!! url('loan_applications/precompute') !!}',
			             dataType: 'json',
			             data:
			             {
			               loan_application_amount: document.getElementById('amount').value,
			               filing_fee: document.getElementById('filing_fee').value,
			               service_fee: document.getElementById('service_fee').value,
			               disbursement_date: document.getElementById('disbursement_date').value,
			               payment_term_id: document.getElementById('payment_term_id').value,
			               payment_schedule_id: document.getElementById('payment_schedule_id').value,
			               interest_id: document.getElementById('loan_interest_id').value
			             },
			             }).success(function(response) {
			               var trHTML = '';
			               for (i = 0; i < response.payment_periods.length; i++)
			               {
			                 trHTML += '<tr><td>' + response.payment_periods[i] + '</td><td>PHP ' + parseFloat(response.periodic_rate).toFixed(2) + '</td></tr>';
			               }
			               $('#pre_payment_scheds').append(trHTML);

			               var table = $('#pre_payment_scheds').DataTable();
			               $('.pre_results').html('<p>Total Loan: <strong>PHP '+ parseFloat(response.total_loan).toFixed(2)+'</strong></p><p>Interest: <strong>PHP '+ parseFloat(response.monthly_interest).toFixed(2)+'</strong></p><p>Payment Collections: <strong>'+response.payment_count+'</strong></p><hr>')
			             });
			     });
			   </script>
	@endsection
@endsection

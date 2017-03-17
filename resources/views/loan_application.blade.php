@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        Loan Application<small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('backpack.base.route_prefix')) }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">Loan Application</li>
      </ol>
    </section>
@endsection

@section('content')
	<div class="row">
  <form action="{{url('admin/loan_applications/save')}}" method="POST">
    {{ csrf_field() }}
    <div class="col-md-8">
      <div class="panel panel-primary">
        <div class="panel-heading">
          Loan Application Details
        </div>

        <div class="panel-body">
              @if (count($errors) > 0)
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif
              <!-- Date & Time Form Group -->
              <div class="form-group">
                  <label for="time_date" class="control-label">
                      Application Date & Time
                  </label>
                  <div>
                      <?php
                          $timestamp = time()+date("Z");
                          echo gmdate("Y/m/d H:i:s",$timestamp);
                      ?>
                  </div>
              </div>

              <!-- Amount Form Group -->
              <div class="form-group">
                <label for="amount" class="control-label">Loan Amount</label>
                <div class="input-group">
                  <span class="input-group-addon">₱</span>
                  <input id="amount" type="text" name="amount" class="form-control" autocomplete="off">
                  <span class="input-group-addon">.00</span>
                </div>
              </div>

              <!-- Purpose Form Group -->
              <div class="form-group">
                <label for="purpose" class="control-label">Purpose</label>
                <textarea name="purpose" class="form-control" autocomplete="off"></textarea>
              </div>

              <!-- Payment Terms x Loan Interest Row Group -->
              <div class="row">
                <div class="col-md-3 col-sm-5">
                    <!-- Payment Terms Form Group -->
                    <div class="form-group">
                      <label for="payment_term_id" class="control-label">Payment Terms</label>
                      <div class="input-group">
                        {{ Form::select('payment_term_id', $payment_terms, null, ['class' => 'form-control', 'id' => 'payment_term_id']) }}
                      </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-5">
                    <!-- Loan Interest Form Group -->
                    <div class="form-group">
                      <label for="loan_interest_id" class="control-label">Loan Interest</label>
                      <div class="input-group">
                        {{ Form::select('loan_interest_id', $loan_interests, null, ['class' => 'form-control', 'id' => 'loan_interest_id']) }}
                      </div>
                    </div>              
                </div>

                <div class="col-md-3 col-sm-5">
                    <!-- Loan Interest Form Group -->
                    <div class="form-group">
                      <label for="payment_schedule_id" class="control-label">Payment Schedule</label>
                      <div class="input-group">
                        {{ Form::select('payment_schedule_id', $payment_schedules, null, ['class' => 'form-control', 'id' => 'payment_schedule_id']) }}
                      </div>
                    </div>              
                </div>  
              </div>

              <div class="row">

                <!-- Filing Fees Form Group -->
                <div class="col-md-6 col-sm-10">
                  <div class="form-group">
                    <label for="filing_fee" class="control-label">Filing Fee</label>
                    <div class="input-group">
                      <span class="input-group-addon">₱</span>
                      <input type="text" id="filing_fee" name="filing_fee" class="form-control" autocomplete="off">
                      <span class="input-group-addon">.00</span>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-sm-10">
                  <!-- Service Fee Form Group -->
                  <div class="form-group">
                    <label for="service_fee" class="control-label">Service Fee</label>
                    <div class="input-group">
                      <span class="input-group-addon">₱</span>
                      <input type="text" id="service_fee" name="service_fee" class="form-control" autocomplete="off">
                      <span class="input-group-addon">.00</span>
                    </div>
                  </div>
                </div>

              </div>

              <div class="row">
                <div class="col-md-3 col-sm-7">
                  <!-- Loan Disbursement Date Form Group -->
                  <div class="form-group">
                    <label for="disbursement_date" class="control-label">Loan Disbursement Date </label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      <input id="disbursement_date" class="datepicker" name="service_fee" class="form-control">
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

              <hr>

              <button type="submit" class="btn btn-block btn-primary btn-sm">Submit Loan Application</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          Select
        </div>

        <div class="panel-body">
          <h4>Choose the Borrower</h4>
          <table class="display responsive no-wrap" id="users-table" width="100%" data-page-length='5'>
            <thead>
              <tr>
                <th>Type</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Select</th>
              </tr>
            </thead>
          </table>
          <hr>
          <!-- Co-Maker Form Group -->
          <div class="form-group">
            <h4>Choose the first Co-Maker</h4>
            <div class="input-group">
              <table class="table table-bordered" id="comaker1-table" data-page-length='5'>
                <thead>
                  <tr>
                    <th>Type</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Select</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
          <hr>
          <!-- Co-Maker Form Group -->
          <div class="form-group">
            <h4>Choose the second Co-Maker</h4>
            <div class="input-group">
              <table class="table table-bordered" id="comaker2-table" data-page-length='5'>
                <thead>
                  <tr>
                    <th>Type</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Select</th>
                  </tr>
                </thead>
              </table>
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
                        <div class="results">
                          
                        </div>
                        <table class="table table-hover table-responsive" id="payment_scheds" data-page-length='5'>
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
                                id="btn-save" >
                            Okay, got it!
                        </button>
                    </div>
                </div>
            </div>
        </div>
  </div>
    
	</div>
  @section('after_scripts')
  <script type="text/javascript">
    $(document).ready(function() {
      $('#users-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: '{!! url('admin/borrowers_data') !!}',
        columns: [
          {data: 0, name: 'borrower_type'},
          {data: 1, name: 'borrower_last_name'},
          {data: 2, name: 'borrower_first_name'},
          {data: 3, name: 'borrower_middle_name'},
          {data: 6, name: 'Actions', orderable: false, searchable: false}
        ]
      });
    });

    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        startDate: '-3d'
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#comaker1-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: '{!! url('admin/comaker1_data') !!}',
        columns: [
          {data: 0, name: 'borrower_type'},
          {data: 1, name: 'borrower_last_name'},
          {data: 2, name: 'borrower_first_name'},
          {data: 3, name: 'borrower_middle_name'},
          {data: 6, name: 'Actions', orderable: false, searchable: false}
        ]
      });
    });
  </script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#comaker2-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: '{!! url('admin/comaker2_data') !!}',
        columns: [
          {data: 0, name: 'borrower_type'},
          {data: 1, name: 'borrower_last_name'},
          {data: 2, name: 'borrower_first_name'},
          {data: 3, name: 'borrower_middle_name'},
          {data: 6, name: 'Actions', orderable: false, searchable: false}
        ]
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
              $('#payment_scheds').append(trHTML);

              var table = $('#payment_scheds').DataTable();
              $('.results').html('<p>Total Loan: <strong>PHP '+ parseFloat(response.total_loan).toFixed(2)+'</strong></p><p>Interest: <strong>PHP '+ parseFloat(response.monthly_interest).toFixed(2)+'</strong></p><p>Payment Collections: <strong>'+response.payment_count+'</strong></p><hr>')
            });
    });
  </script>


  @endsection
@endsection
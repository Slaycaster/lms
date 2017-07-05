@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h4>
        Loan Application<small>All active Loan Applications in the database.</small>
      </h4>
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
    <div class="col-md-12">
      <div class="panel panel-primary">
        <div class="panel-heading">
          Loan Application Form
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
              @if (Session::has('message'))
                  <div class="alert alert-info">{{ Session::get('message') }}</div>
              @endif
              @if (Session::has('error_message'))
                  <div class="alert alert-danger">{{ Session::get('error_message') }}</div>
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

              <!-- Client Form Group -->
              <div class="form-group">
                  <label for="borrower">
                    Client
                  </label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-address-book"></i></span>
                    <div class="row">
                      <div class="col-md-3">
                        <input type="text" id="Text2"/>
                      </div>
                      <div class="col-md-2">
                        <p><strong>Client ID: </strong><span id="ta-id"></span></p>
                        <input type="hidden" name="borrower_id" id="borrower_id">
                      </div>
                      <div class="col-md-3">
                        <p><strong>Selected Client: </strong><span id="ta-txt"></span></p>
                      </div>
                    </div>
                  </div>
              </div>

              <!-- Co-Maker Form Group -->
              <div class="form-group">
                  <label for="borrower">
                    Co-Maker 1
                  </label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-address-book"></i></span>
                    <div class="row">
                      <div class="col-md-3">
                        <input type="text" id="comaker1"/>
                      </div>
                      <div class="col-md-2">
                        <p><strong>Co-Maker 1 ID: </strong><span id="comaker1-id"></span></p>
                        <input type="hidden" name="comaker1_id" id="comaker1_id">
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
                    Co-Maker 2
                  </label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-address-book"></i></span>
                    <div class="row">
                      <div class="col-md-3">
                        <input type="text" id="comaker2"/>
                      </div>
                      <div class="col-md-2">
                        <p><strong>Co-Maker 2 ID: </strong><span id="comaker2-id"></span></p>
                        <input type="hidden" name="comaker2_id" id="comaker2_id">
                      </div>
                      <div class="col-md-3">
                        <p><strong>Selected Co-Maker 2: </strong><span id="comaker2-txt"></span></p>
                      </div>
                    </div>
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

              <!-- Filing Fees x Service Fee x Payment Type Form Group-->
              <div class="row">

                <!-- Filing Fees Form Group -->
                <div class="col-md-3 col-sm-8">
                  <div class="form-group">
                    <label for="filing_fee" class="control-label">Filing Fee</label>
                    <div class="input-group">
                      <span class="input-group-addon">₱</span>
                      <input type="text" id="filing_fee" name="filing_fee" class="form-control" autocomplete="off">
                    </div>
                  </div>
                </div>

                <div class="col-md-3 col-sm-8">
                  <!-- Service Fee Form Group -->
                  <div class="form-group">
                    <label for="service_fee" class="control-label">Service Fee</label>
                    <div class="input-group">
                      <span class="input-group-addon">₱</span>
                      <input type="text" id="service_fee" name="service_fee" class="form-control" autocomplete="off">
                    </div>
                  </div>
                </div>

                <div class="col-md-3 col-sm-8">
                  <!-- Service Fee Form Group -->
                  <div class="form-group">
                    <label for="service_fee" class="control-label">Filing Fee & Service Fee Payment Type</label>
                    <div class="input-group">
                      {{ Form::select('filing_service_payment_type', array('0' => 'One-time Payment', '1' => 'Amortized without Interest', '2' => 'Amortized with Interest'), null, array('class' => 'form-control', 'id' => 'filing_service_payment_type')) }}
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
                      <input id="disbursement_date" class="datepicker" name="disbursement_date" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="col-md-3 col-sm-7">
                  <!-- Start Collection Date Form Group -->
                  <div class="form-group">
                    <label for="collection_date" class="control-label">Start Collection Date </label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      <input id="collection_date" class="datepicker" name="collection_date" class="form-control">
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

              <button type="submit" class="btn btn-block btn-success btn-sm">Submit Loan Application</button>
        </div>
      </div>
    </div>

          </form>
        <!-- Modal (Pop up when detail button clicked) -->
        <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <i class="fa fa-circle-o-notch fa-4x"></i>
                        <h4 class="modal-title" id="myModalLabel"><b>Pre-computed Payment Schedule</b></h4>
                        <a href="" class="btn btn-default pull-right"><span class="fa fa-print"></span> Print</a>
                    </div>


                     <div class="modal-body">
                        <div class="results">

                        </div>
                        <div class="table-responsive">
                          <table class="table table-hover table-responsive" id="payment_scheds" data-page-length='5'>
                            <thead>
                              <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Principal</th>
                                <th>Interest</th>
                                <th>Filing Fee</th>
                                <th>Service Fee</th>
                              </tr>
                              <tbody>

                              </tbody>
                            </thead>
                          </table>
                        </div>
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
  </div>

	</div>
  @section('after_scripts')
   <script>
        $(document).ready(function () {
            var text2 = $("#Text2").tautocomplete({
                width: "600px",
                columns: ['Last Name', 'First Name', 'Middle Name', 'Company'],
                placeholder: "Search for Client by Last Name",
                norecord: "No Records Found",
                highlight: "",
                hide: [false],
                ajax: {
                    url: '{!! url('/borrowers_data') !!}',
                    type: "GET",
                    success: function (data) {

                        var filterData = [];

                        var searchData = eval("/" + text2.searchdata() + "/gi");

                        $.each(data, function (i, v) {
                            if (v.borrower_last_name.search(new RegExp(searchData)) != -1) {
                                filterData.push(v);
                            }
                        });
                        return filterData;
                    }
                },
                onchange: function () {
                    $("#ta-txt").html(text2.text());
                    $("#ta-id").html(text2.id());
                    document.getElementById('borrower_id').value = text2.id();
                }
            });

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
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        startDate: '-3d'
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
              filing_service_payment_type: document.getElementById('filing_service_payment_type').value,
              disbursement_date: document.getElementById('disbursement_date').value,
              collection_date: document.getElementById('collection_date').value,
              payment_term_id: document.getElementById('payment_term_id').value,
              payment_schedule_id: document.getElementById('payment_schedule_id').value,
              interest_id: document.getElementById('loan_interest_id').value
            },
            }).success(function(response) {
              var trHTML = '';
               $('#pre_payment_scheds').html("<thead><tr><th>Date</th><th>Amount</th><th>Principal</th><th>Interest</th><th>Filing Fee</th><th>Service Fee</th></tr><tbody></tbody></thead>");
              for (i = 0; i < response.payment_periods.length; i++)
              {
                trHTML += '<tr><td>' + response.payment_periods[i] + '</td><td>PHP ' + parseFloat(response.periodic_rates[i]).toFixed(2) +  '</td><td>PHP ' + parseFloat(response.periodic_principal_rates[i]).toFixed(2) + '</td><td>PHP ' + parseFloat(response.periodic_interest_rates[i]).toFixed(2) + '</td><td>PHP ' + parseFloat(response.periodic_filing_fee[i]).toFixed(2) + '</td><td>PHP ' + parseFloat(response.periodic_service_fee[i]).toFixed(2) + '</td></tr>';
              }
              $('#payment_scheds').append(trHTML);
              var table = $('#payment_scheds').DataTable();
              
              $('.results').html('<p>Principal: <strong>PHP '+ parseFloat(response.principal_amount).toFixed(2)+'</strong></p><p>Interest: <strong>PHP '+ parseFloat(response.total_interest).toFixed(2)+'</strong><p>Total Fees: <strong>PHP '+ parseFloat(response.total_fees).toFixed(2)+'</strong></p><p>Total Due: <strong>PHP '+ parseFloat(response.total_loan).toFixed(2)+'</strong></p><p>Payment Collections: <strong>'+response.payment_count+'</strong></p><hr>')
            });
    });
  </script>


  @endsection
@endsection

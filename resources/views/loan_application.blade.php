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
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          Select Loan Borrower
        </div>

        <div class="panel-body">
          <table class="table table-bordered table-responsive" id="users-table">
            <thead>
              <tr>
                <th>Type</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Employment Date</th>
                <th>Assignment Date</th>
                <th>Select</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
		<div class="col-md-6">
			<div class="panel panel-primary">
        <div class="panel-heading">
          Loan Application Details
        </div>

        <div class="panel-body">
          <form>
              <!-- Date & Time Form Group -->
              <div class="form-group">
                  <label for="time_date" class="control-label">
                      Date & Time
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
                  <input type="text" name="amount" class="form-control">
                  <span class="input-group-addon">.00</span>
                </div>
              </div>

              <!-- Purpose Form Group -->
              <div class="form-group">
                <label for="purpose" class="control-label">Purpose</label>
                <textarea name="purpose" class="form-control"></textarea>
              </div>


              <!-- Co-Maker Row Group -->
              <div class="row">
                <div class="col-xs-6 col-sm-4">
                    <!-- Co-Maker Form Group -->
                    <div class="form-group">
                      <label for="comaker1" class="control-label">Co-Maker 1</label>
                      <div class="input-group">
                        <input type="text" name="comaker1" class="form-control">
                      </div>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-4">
                    <!-- Co-Maker Form Group -->
                    <div class="form-group">
                      <label for="comaker2" class="control-label">Co-Maker 2</label>
                      <div class="input-group">
                        <input type="text" name="comaker2" class="form-control">
                      </div>
                    </div>
                </div>
              </div>

              <!-- Payment Terms x Loan Interest Row Group -->
              <div class="row">
                <div class="col-xs-6 col-sm-4">
                    <!-- Payment Terms Form Group -->
                    <div class="form-group">
                      <label for="payment_term_id" class="control-label">Payment Terms</label>
                      <div class="input-group">
                        {{ Form::select('payment_term_id', $payment_terms, null, ['class' => 'form-control']) }}
                      </div>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-4">
                    <!-- Loan Interest Form Group -->
                    <div class="form-group">
                      <label for="loan_interest_id" class="control-label">Loan Interest</label>
                      <div class="input-group">
                        {{ Form::select('loan_interest_id', $loan_interests, null, ['class' => 'form-control']) }}
                      </div>
                    </div>              
                </div>                
              </div>

              <button type="submit" class="btn btn-block btn-primary btn-sm">Submit Loan Application</button>
          </form>
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
        ajax: '{!! url('admin/borrowers_data') !!}',
        columns: [
          {data: 0, name: 'borrower_type'},
          {data: 1, name: 'borrower_last_name'},
          {data: 2, name: 'borrower_first_name'},
          {data: 3, name: 'borrower_middle_name'},
          {data: 4, name: 'borrower_employment_date'},
          {data: 5, name: 'borrower_assignment_date'},
          {data: 6, name: 'Actions', orderable: false, searchable: false}
        ]
      });
    });
  </script>
  @endsection
@endsection
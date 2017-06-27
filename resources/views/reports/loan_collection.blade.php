@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        Loan Collection Report<small>Generate Loan Collection report for the given cycle.</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('backpack.base.route_prefix')) }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">Loan Collection Report</li>
      </ol>
    </section>
    
@endsection

@section('content')
  <div class="row">
    <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-heading">
          
        </div>

        <div class="panel-body">
          <form method="get" action="{{url('admin/reports/loan_collections/pdf')}}" target="_blank">
            {{ csrf_field() }}
          @if (Auth::user()->company->id == 1)
            <h4><span class = "fa fa-clock-o"></span> Select Company</h4>
            {{ Form::select('company_id', $companies, null, array('class' => 'form-control', 'id' => 'drop'))}}
            {{ Form::hidden('company_id_hidden', Auth::user()->company->id, array('id' => 'company_id')) }}
            <hr>
          @else
            {{ Form::hidden('company_id', Auth::user()->company->id, array('id' => 'company_id')) }}
          @endif  
            <div class="form-group">
                <h4><span class = "fa fa-calendar-o"></span> Choose Collection Cycle Date</h4>

                <div class="input-group date">
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="date-table">
                        <thead>
                          <tr>
                            <th>Collection Date</th>
                            <th>Select</th>
                          </tr>
                      </thead>
                    </table>
                  </div>
                </div>
                
            </div>
                  

            <button type="submit" class="btn btn-primary btn-block">View</a>
          </form>
        </div>
      </div>
    </div>
  </div>

  @section('after_scripts')
    <script type="text/javascript">
      $(document).ready(function () {
        $('#datepicker').datepicker({
              format: "yyyy-mm-dd"
          });
      });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
          var company_id = document.getElementById('company_id').value;

          init_table = $('#date-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! url('payment_collections/dates') !!}' + '/' + company_id,
            columns: [
              {data: '0', name: 'payment_collections.payment_collection_date'},
              {data: '1', name: 'Select', orderable: false, searchable: false}
            ]
          });
        });
    </script>

    <script type="text/javascript">
      $("#drop").change(function () {
          var company_id_change = document.getElementById('drop').value;
          
          init_table.destroy();

          init_table = $('#date-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! url('payment_collections/dates') !!}' + '/' + company_id_change,
            columns: [
              {data: '0', name: 'payment_collections.payment_collection_date'},
              {data: '1', name: 'Select', orderable: false, searchable: false}
            ]
          });          
      });
    </script>

  @endsection
@endsection
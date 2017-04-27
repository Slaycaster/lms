@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        Loan Collection Report<small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('backpack.base.route_prefix')) }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">Loan Collection Report</li>
      </ol>
    </section>
    
@endsection

@section('content')
  <div class="row">
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          
        </div>

        <div class="panel-body">
          <h4><span class = "fa fa-clock-o"></span> Select Company</h4>
          <hr>
          <form method="get" action="{{url('admin/reports/loan_collections/pdf')}}" target="_blank">
            {{ csrf_field() }}
            
            <div class="form-group">
                      <label>Date:</label>

                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" data-date-format="yyyy-mm-dd" id="datepicker" name="date">
                      </div>
                      
                  </div>
                  

            {{ Form::select('company_id', $companies, null, array('class' => 'form-control'))}}
            <br>
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
  @endsection
@endsection
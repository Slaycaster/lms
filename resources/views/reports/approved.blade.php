@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        Approved Loan Application Report<small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('backpack.base.route_prefix')) }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">Approved Loan Application Reports</li>
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
          <h4><span class = "fa fa-clock-o"></span> By Month</h4>
          <hr>
          <form method="post" action="{{url('reports/loan_applications/pdf')}}" target="_blank">
            <div class="form-group">
                      <label>Date:</label>

                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" data-date-format="yyyy/mm/dd" id="datepicker" name="date">
                      </div>
                      <!-- /.input group -->
                  </div>

            {{ Form::select('company_id', $companies, null, array('class' => 'form-control'))}}
            <br>
            <button type="submit" class="btn btn-primary btn-block">View</a>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
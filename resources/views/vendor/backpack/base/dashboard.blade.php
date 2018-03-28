@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        {{ trans('backpack::base.dashboard') }}
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('backpack.base.route_prefix', 'admin')) }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
      </ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="box-title"><small>Loan Management System</small></div>
                </div>
                <div class="box-body">
                    <img src="{{ asset('img/mooloans_logo_web.jpg') }}" class="img-responsive">
                </div>
            </div>
        </div>
    </div>
@endsection

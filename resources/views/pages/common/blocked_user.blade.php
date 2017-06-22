@extends('layouts.default')

@section('page')
  <div id="page-common-blocked-user">
    @parent
  </div>
@stop

@section('content')
  <div class="container">
    <div class="row justify-content-center" style="margin-top: 2rem;">
      <div class="col-sm-12">

        <div class="mx-auto" style="max-width: 300px;">
          <h1 style="margin-top: 80px"><i class="fa fa-check" aria-hidden="true" style="color: #4285F4"></i> Thank You</h1>
          <p>We'll be in touch within 24 hours about activating your account.</p>
        </div>
      </div>
    </div>
  </div>
@stop

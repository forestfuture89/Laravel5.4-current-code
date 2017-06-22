@extends('layouts.default')

@section('page')
  <div id="page-error-404">
    @parent
  </div>
@stop

@section('content')
  <div class="container error-page">
    <ol class="breadcrumb mt-2">
      <li class="breadcrumb-item"><a href="{{ URL::to('/') }}">Dashboard</a></li>
      <li class="breadcrumb-item active">404 Error</li>
    </ol>
    <div class="row justify-content-center">
      <div class="col-sm-10 col-md-6">
        <div class="card mt-4">
          <div class="form-header" style="margin-bottom: 5px">
            <h3><i class="fa fa-bug" aria-hidden="true"></i> &nbsp;Oops! Page not found</h3>
          </div>
          <div class="card-block">
            <p class="text-center" style="margin-bottom: 12px">
              We could not find the page you were looking for.
              Meanwhile, you may <a href="{{ URL::to('/') }}">return to dashboard</a> or try using the search form.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

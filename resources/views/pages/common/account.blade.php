@extends('layouts.default')

@section('style')
  <style>
    .err-msg {
      font-size: 1.4rem;
      text-align: right;
      margin-top: -5px;
    }
  </style>
@stop

@section('page')
  <div id="page-account">
    @parent
  </div>
@stop

@section('content')
  <div class="container">
    <ol class="breadcrumb mt-2">
      <li class="breadcrumb-item"><a href="{{ URL::to('/') }}">Tender</a></li>
      <li class="breadcrumb-item active">Account</li>
    </ol>
    <div class="row justify-content-center">
      <div class="col-sm-10 col-md-6">

        <!-- Message for update success -->
        @if( Session::has('update_success'))
          <div class="alert alert-success alert-dismissible" role="alert" id="alert" style="text-align: center;font-size: 14px;margin-top: 25px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <strong>
              {{ Session::get('update_success') }}
            </strong>
          </div>
        @endif

        <div class="card mt-4">
          <div class="form-header">
            <h3>Account</h3>
          </div>
          <div class="card-block">
            <form role="form" method="POST" action="{{ Request::url() }}">
            {{ csrf_field() }}

              <div class="md-form">
                <i class="fa fa-user prefix"></i>
                <input type="text" id="name"
                       class="form-control @if ($errors->has('name')) invalid @endif" name="name"
                       value="{{ old('name', $user->name) }}" required>
                <label for="name">Name</label>
                @if ($errors->has('name'))
                  <p class="text-danger err-msg">{{ $errors->first('name') }}</p>
                @endif
              </div>
              <div class="md-form">
                <i class="fa fa-building-o prefix"></i>
                <input type="text" id="company_name"
                       class="form-control @if ($errors->has('company_name')) invalid @endif" name="company_name"
                       value="{{ old('company_name', $user->company()->name) }}" required>
                <label for="company_name">Company Name</label>
                @if ($errors->has('company_name'))
                  <p class="text-danger err-msg">{{ $errors->first('company_name') }}</p>
                @endif
              </div>
              <div class="md-form">
                <i class="fa fa-at prefix"></i>
                <input type="text" id="job_title"
                       class="form-control @if ($errors->has('job_title')) invalid @endif" name="job_title"
                       value="{{ old('job_title', $user->job_title) }}">
                <label for="job_title">Job Title</label>
                @if ($errors->has('job_title'))
                  <p class="text-danger err-msg">{{ $errors->first('job_title') }}</p>
                @endif
              </div>
              <div class="md-form">
                <i class="fa fa-envelope prefix"></i>
                <input type="text" id="email"
                       class="form-control @if ($errors->has('email')) invalid @endif" name="email"
                       value="{{ old('email', $user->email) }}" required>
                <label for="email">Email</label>
                @if ($errors->has('email'))
                  <p class="text-danger err-msg">{{ $errors->first('email') }}</p>
                @endif
              </div>
              <div class="md-form">
                <i class="fa fa-phone prefix"></i>
                <input type="text" id="phone"
                       class="form-control @if ($errors->has('phone')) invalid @endif" name="phone"
                       value="{{ old('phone', $user->phone) }}">
                <label for="phone">Phone</label>
                @if ($errors->has('phone'))
                  <p class="text-danger err-msg">{{ $errors->first('phone') }}</p>
                @endif
              </div>
              <div class="md-form">
                <i class="fa fa-lock prefix"></i>
                <input type="password" id="password" class="form-control @if ($errors->has('password')) invalid @endif" name="password">
                <label for="password">New Password</label>
                @if ($errors->has('password'))
                  <p class="text-danger err-msg">{{ $errors->first('password') }}</p>
                @endif
              </div>
              <div class="text-center">
                <button type="submit" class="btn primary-color-dark">Update Account</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
  <script>
      $(function() {
          $("#phone").mask("999-999-9999");
      });
  </script>
@stop

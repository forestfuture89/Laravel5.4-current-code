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
  <div id="page-pwd-reset-request-email">
    @parent
  </div>
@stop

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-sm-10 col-md-6">
        <div class="card mt-4">
          <div class="form-header">
            <h3><i class="fa fa-lock"></i> Reset Password</h3>
          </div>
          <div class="card-block login">
            @if (session('status'))
              <div class="alert alert-success alert-dismissible" role="alert" style="font-size: 14px;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                {{ session('status') }}
              </div>
            @endif

            <form role="form" method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}

              <div class="md-form">
                <i class="fa fa-envelope prefix"></i>
                <input id="email" type="email" class="form-control {{ $errors->has('email') ? 'invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                <label for="email">Email Address</label>
                @if ($errors->has('email'))
                  <p class="text-danger err-msg">{{ $errors->first('email') }}</p>
                @endif
              </div>
              <div class="text-center">
                <button type="submit" class="btn primary-color-dark">Send Password Reset Link</button>
              </div>
            </form>
          </div>
        </div>
        <div class="modal-footer">
          <div class="options">
            <p>Go back to <a href="{{ route('login') }}">Login</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

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
  <div id="page-pwd-reset">
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

            <form role="form" method="POST" action="{{ route('password.request') }}">
              {{ csrf_field() }}

              <input type="hidden" name="token" value="{{ $token }}">

              <div class="md-form">
                <i class="fa fa-envelope prefix"></i>
                <input id="email" type="email" class="form-control {{ $errors->has('email') ? 'invalid' : '' }}" name="email"
                       value="{{ $email or old('email') }}" required autofocus>
                <label for="email">Email Address</label>
                @if ($errors->has('email'))
                  <p class="text-danger err-msg">{{ $errors->first('email') }}</p>
                @endif
              </div>
              <div class="md-form">
                <i class="fa fa-lock prefix"></i>
                <input id="password" type="password" class="form-control {{ $errors->has('password') ? 'invalid' : '' }}" name="password" required>
                <label for="password">Password</label>
                @if ($errors->has('password'))
                  <p class="text-danger err-msg">{{ $errors->first('password') }}</p>
                @endif
              </div>
              <div class="md-form">
                <i class="fa fa-lock prefix"></i>
                <input id="password_confirmation" type="password" class="form-control {{ $errors->has('password_confirmation') ? 'invalid' : '' }}"
                       name="password_confirmation" required>
                <label for="password_confirmation">Confirm Password</label>
                @if ($errors->has('password_confirmation'))
                  <p class="text-danger err-msg">{{ $errors->first('password_confirmation') }}</p>
                @endif
              </div>

              <div class="text-center">
                <button type="submit" class="btn primary-color-dark">Reset Password</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@extends('layouts.default')

@section('page')
  <div id="page-login">
    @parent
  </div>
@stop

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-sm-10 col-md-6">
        <div class="card mt-4">
          <!--Header-->
          <div class="form-header">
            <h3><i class="fa fa-lock"></i> Login</h3>
          </div>

          <!--Body-->
          <div class="card-block login">
            <form role="form" method="POST" action="{{ Request::url() }}">
            {{ csrf_field() }}

              @if ($errors->has('email'))
                <p class="text-danger" style="font-size: 1.4rem;text-align: right;margin-top: -5px;">{{ $errors->first('email') }}</p>
              @elseif ($errors->has('password'))
                <p class="text-danger" style="font-size: 1.4rem;text-align: right;margin-top: -5px;">{{ $errors->first('password') }}</p>
              @endif
              <div class="md-form">
                <i class="fa fa-envelope prefix"></i>
                <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}"
                       required>
                <label for="email">Your email</label>
              </div>
              <div class="md-form">
                <i class="fa fa-lock prefix"></i>
                <input type="password" id="password" class="form-control" name="password" required>
                <label for="password">Your password</label>
              </div>
              <div class="text-center">
                <button type="submit" class="btn primary-color-dark">Login</button>
              </div>

            </form>
          </div>
        </div>
        <div class="modal-footer">
          <div class="options">
            <p>Don't have an account? <a href="{{ route('register') }}">Sign Up</a></p>
            <p><a href="{{ route('password.request') }}">Forgot Password?</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

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
  <div id="page-signup">
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
            <h3><i class="fa fa-lock"></i> Sign Up</h3>
          </div>

          <!--Body-->
          <div class="card-block signup">
            <form role="form" method="POST" action="{{ Request::url() }}">
            {{ csrf_field() }}

              <div class="md-form">
                <i class="fa fa-user prefix"></i>
                <input type="text" id="name" class="form-control @if ($errors->has('name')) invalid @endif" name="name" value="{{ old('name') }}" required>
                <label for="name">Name</label>
                @if ($errors->has('name'))
                  <p class="text-danger err-msg">{{ $errors->first('name') }}</p>
                @endif
              </div>
              <div class="md-form">
                <i class="fa fa-building-o prefix"></i>
                <input type="text" id="company_name" class="form-control @if ($errors->has('company_name')) invalid @endif" name="company_name" value="{{ old('company_name') }}" required>
                <label for="company_name">Company Name</label>
                @if ($errors->has('company_name'))
                  <p class="text-danger err-msg">{{ $errors->first('company_name') }}</p>
                @endif
              </div>
              <div class="md-form">
                <i class="fa fa-at prefix"></i>
                <input type="text" id="job_title" class="form-control @if ($errors->has('job_title')) invalid @endif" name="job_title" value="{{ old('job_title') }}" required>
                <label for="job_title">Job Title</label>
                @if ($errors->has('job_title'))
                  <p class="text-danger err-msg">{{ $errors->first('job_title') }}</p>
                @endif
              </div>
              <div class="md-form">
                <i class="fa fa-location-arrow prefix"></i>
                <input type="text" id="company_website" class="form-control @if ($errors->has('company_website')) invalid @endif" name="company_website" value="{{ old('company_website') }}" required>
                <label for="company_website">Website</label>
                @if ($errors->has('company_website'))
                  <p class="text-danger err-msg">{{ $errors->first('company_website') }}</p>
                @endif
              </div>
              <div class="md-form">
                <i class="fa fa-phone prefix"></i>
                <input type="text" id="phone" class="form-control @if ($errors->has('phone')) invalid @endif" name="phone" value="{{ old('phone') }}" required>
                <label for="phone">Phone</label>
                @if ($errors->has('phone'))
                  <p class="text-danger err-msg">{{ $errors->first('phone') }}</p>
                @endif
              </div>
              <div class="md-form">
                <i class="fa fa-envelope prefix"></i>
                <input type="email" id="email" class="form-control @if ($errors->has('email')) invalid @endif" name="email" value="{{ old('email') }}" required>
                <label for="email">Email</label>
                @if ($errors->has('email'))
                  <p class="text-danger err-msg">{{ $errors->first('email') }}</p>
                @endif
              </div>
              <div class="md-form">
                <i class="fa fa-lock prefix"></i>
                <input type="password" id="password" class="form-control @if ($errors->has('password')) invalid @endif" name="password" required>
                <label for="password">Password</label>
                @if ($errors->has('password'))
                  <p class="text-danger err-msg">{{ $errors->first('password') }}</p>
                @endif
              </div>
              <div class="md-form">
                <i class="fa fa-lock prefix"></i>
                <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" required>
                <label for="password_confirmation">Confirm Password</label>
              </div>
              <div class="md-form">
                <select id="company_type" class="mdb-select" name="company_type">
                  <option value="" disabled @if (is_null(old('company_type'))) selected @endif>Company Type</option>
                  <option value="client" @if (old('company_type') == 'client') selected @endif>E&P Operator</option>
                  <option value="provider" @if (old('company_type') == 'provider') selected @endif>Service Provider</option>
                </select>
                @if ($errors->has('company_type'))
                  <p class="text-danger err-msg">{{ $errors->first('company_type') }}</p>
                @endif
              </div>
              <div class="text-center">
                <button type="submit" class="btn primary-color-dark">Sign up</button>
              </div>

            </form>
          </div>
        </div>
        <div class="modal-footer">
          <div class="options">
            <p>
              Already have an account?
              <a href="{{ route('login') }}">Login</a>
            </p>
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

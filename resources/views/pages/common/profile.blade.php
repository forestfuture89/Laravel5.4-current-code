@extends('layouts.default')

@section('page')
  <div id="page-profile">
    @parent
  </div>
@stop

@section('content')
  <div class="container">
    <ol class="breadcrumb mt-2">
      <li class="breadcrumb-item"><a href="{{ URL::to('/') }}">Dashboard</a></li>
      <li class="breadcrumb-item active">Profile</li>
    </ol>
    <div class="row justify-content-center">
      <div class="col-sm-10 col-md-6">
        <div class="card mt-4">
          <div class="form-header">
            <h3><i class="fa fa-user" aria-hidden="true"></i> &nbsp;{{ $user->name }}</h3>
          </div>
          <div class="card-block">
            <div class="view-control">
              <span class="control-title">
                  Company Name:
              </span>
              <span class="control-value">{{ $user->company()->name }}</span>
            </div>
            <div class="view-control">
              <span class="control-title">
                  Job Title:
              </span>
              <span class="control-value">{{ $user->job_title }}</span>
            </div>
            <div class="view-control">
              <span class="control-title">
                  Company Type:
              </span>
              <span class="control-value">
                @if ($user->company()->type == 1)
                E&amp;P Operator
                @elseif ($user->company()->type == 2)
                Service Provider
                @else
                Other
                @endif
              </span>
            </div>
            <div class="view-control">
              <span class="control-title">
                  Email:
              </span>
              <span class="control-value"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></span>
            </div>
            <div class="view-control">
              <span class="control-title">
                  Phone:
              </span>
              <span class="control-value"><a href="tel:{{ $user->phone }}">{{ $user->phone }}</a></span>
            </div>
            @if (Auth::user()->admin == 1 && Auth::user() !=  $user)
            <div class="card-block text-center">
              <a class="btn btn-sm blue-grey" href="{{route('admin.switch', ['id' => $user->id])}}">
                @if ($user->blocked == 0)
                Deactivate User
                @elseif ($user->blocked == 1)
                Activate User
                @endif
              </a>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@extends('layouts.default')

@section('page')
  <div id="page-error-500">
    @parent
  </div>
@stop

@section('content')
  <div class="container">
    <ol class="breadcrumb mt-2">
      <li class="breadcrumb-item"><a href="{{ URL::to('/') }}">Dashboard</a></li>
      <li class="breadcrumb-item active">Error</li>
    </ol>
    <div class="row justify-content-center">
      <div class="col-sm-10 col-md-6">
        <div class="card mt-4">
          <div class="form-header" style="margin-bottom: 5px">
            <h3><i class="fa fa-bug" aria-hidden="true"></i> &nbsp;Application Error</h3>
          </div>
          <div class="card-block">
              <p class="text-center" style="margin-bottom: 12px">Sorry, we had a problem with your request.</p>
              @unless(empty($sentryID))
                  <!-- Sentry JS SDK 2.1.+ required -->
                  <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

                  <script>
                  Raven.showReportDialog({
                      eventId: '{{ $sentryID }}',

                      // use the public DSN (dont include your secret!)
                      dsn: 'https://0ddf6778330743c189c74349238aa639@sentry.io/169360'
                  });
                  </script>
              @endunless
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1"/>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <link rel="shortcut icon" type="image/x-icon" href="https://secure.gravatar.com/blavatar/03a67cdff220c398f097e854f8d07599?s=32" sizes="16x16" />
  <link rel="icon" type="image/x-icon" href="https://secure.gravatar.com/blavatar/03a67cdff220c398f097e854f8d07599?s=32" sizes="16x16" />
  <link rel="apple-touch-icon-precomposed" href="https://secure.gravatar.com/blavatar/03a67cdff220c398f097e854f8d07599?s=114" />

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Pusher Key & Cluster -->
  <meta name="pusher-key" content="{{ config('app.pusher_key') }}">
  <meta name="pusher-cluster" content="{{ config('app.pusher_cluster') }}">

  <title>{{ config('app.name', 'OSX') }}</title>

  <!-- Styles -->
  <link href="{{ elixir('css/app.css') }}" rel="stylesheet">
  <!-- Additional Styles -->
  @yield('style')

  <!-- Scripts -->
  <script>
      window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
  </script>
</head>

<body class="osx">

  @section('page')
    <header class="page-header">
      <!-- Navbar -->
      @include('includes.header')
      <!-- End of Navbar -->
    </header>

    <main class="page-main">
      @yield('content')
    </main>

    <footer>
      <!-- Include the /includes/footer.blade.php -->
    </footer>
  @show

  <!-- Scripts -->
  <script src="{{ elixir('js/app.js') }}"></script>
  <!-- Additional Scripts -->
  @yield('script')

</body>
</html>

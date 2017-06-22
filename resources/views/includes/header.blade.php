<nav class="navbar fixed-top navbar-toggleable-md scrolling-navbar navbar-dark bg-primary">
  <div class="container">
    <div class="logo">
      <a href="{{ URL::to('/') }}">
        <img src="{{ asset('assets/images/osx-logo-white.png') }}" alt="logo"/>
        <span>
          The Offshore Exchange
        </span>
      </a>
    </div>

    @if (Auth::check())
    <!-- navBar for authenticated user -->
    <!--<div class="collapse navbar-collapse" id="main_nav">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="{{ URL::to('/') }}">Tender</a>
        </li>
      </ul>
    </div>-->
    <div class="avatar">
      <div class="notification">
        <span id="notification_badge" class="badge notification-alert {{ Auth::user()->countUnreadNotifications() > 0 ? 'show' : 'hide' }}">
          {{ Auth::user()->countUnreadNotifications() }}
        </span>
        <a id="toggle_notification" href="javascript:void(0)"><i class="fa fa-bell" aria-hidden="true"></i></a>
        <div id="notification_block" class="notification-block" data-id="{{ Auth::user()->id }}">
          @forelse (Auth::user()->notifications() as $notification)
            <div class="notification-item {{ $notification->read ? '' : 'unread' }}" data-id="{{ $notification->id }}">
              <a href="{{ route('notification.show', ['id' => $notification->id]) }}">{{ $notification->content() }}</a>
              <span class="freshness">{{ $notification->passedTime() }}</span>
            </div>
          @empty
            <div class="notification-item" style="text-align: center;" data-id="0">
              <span class="freshness">There are no notifications.</span>
            </div>
          @endforelse
          <div class="see-all">
            <a id="mark_read" href="javascript:void(0)" data-url="{{ route('notification.mark.read') }}"
               class="{{ (Auth::user()->countUnreadNotifications() == 0 || Auth::user()->notifications()->isEmpty()) ? 'mark-disable' : '' }}">
              Mark all as read
            </a>
            <span style="border-right: 2px solid #CCC;margin: 0 10px 0 8px;"></span>
            <a href="{{ route('notification.index') }}">See all</a>
          </div>
        </div>
      </div>

      <div class="dropdown">
        <a class="dropdown-toggle d-flex" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="/{{ Auth::user()->pic_path }}" />
          <div class="user">
            <span class="user-name">{{ Auth::user()->name }}</span>
            <span class="user-role">
              @if (Auth::user()->company()->type == 1)
                Admin
              @elseif (Auth::user()->company()->type == 1)
                E&P Operator
              @elseif (Auth::user()->company()->type == 2)
                Provider
              @endif
            </span>
          </div>
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="{{ route('account') }}"><i class="fa fa-user"></i> &nbsp;Account</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout_form').submit();"><i class="fa fa-sign-out"></i> &nbsp;Logout</a>
        </div>
      </div>

      <form id="logout_form" action="{{ route('logout') }}"
            method="POST" style="display: none;">
        {{ csrf_field() }}
      </form>
    </div>
    <!-- End of navBar for authenticated user -->
    @elseif (!Request::is('login') && !Request::is('register'))
    <!-- navBar for guest -->
    <div class="collapse navbar-collapse" id="navbarNav3">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#">Product</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Company</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
      </ul>
    </div>
    <!-- End of navBar for guest -->
    @endif
  </div>
</nav>

@extends('layouts.default')

@section('page')
  <div id="page-notifications">
    @parent
  </div>
@stop

@section('content')
  <div class="container">
    <h2 class="mt-4 mb-4">Notifications</h2>
    <div class="row">
      <div class="col-sm-8"></div>
      <div class="col-sm-4 text-right">
        <a id="mark_read_btn" href="javascript:void(0)" data-url="{{ route('notification.mark.read') }}"
           class="btn btn-sm blue-grey btn-notification-mark {{ (Auth::user()->countUnreadNotifications() == 0 || Auth::user()->notifications()->isEmpty()) ? 'mark-disable' : '' }}">
          Mark all notifications as read
        </a>
      </div>
    </div>
    <table class="table notification-container" id="notificationTable">
      <thead style="display:none;">
        <tr><th></th></tr>
      </thead>
      <tbody>
        @forelse (Auth::user()->notifications() as $notification)
          <tr>
            <td class="notification-cell d-flex align-items-center border-b-1 justify-content-between {{ $notification->read ? '' : 'unread' }}">
              <div class="notification-content">
                <a href="{{ route('notification.show', ['id' => $notification->id]) }}">{{ $notification->content() }}</a>
              </div>
              <div class="notification-time text-center">
                {{ $notification->passedTime() }}
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td class="notification-cell">
              <span>There are no notifications.</span>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection

@section('script')
  <script>
      $(document).ready(function() {
          // Datatable for notification containers
          table = $('#notificationTable').DataTable({
              lengthChange: false,
              order: [],
              pageLength: 10
          });
      });
  </script>
@endsection

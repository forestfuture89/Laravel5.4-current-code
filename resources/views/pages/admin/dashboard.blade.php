@extends('layouts.default')

@section('page')
  <div id="page-admin-dashboard">
    @parent
  </div>
@stop

@section('content')
<div class="container">
    <div class="table-container mt-2">
        <div class="table-helpers" style="float: right">
            <div class="filter">
                <span class="title">
                    Filter:
                </span>
                <div class="md-form filter-date start">
                    <input placeholder="From" type="text" id="date-picker-start" class="datepicker">
                    <label for="date-picker-start"></label>
                </div>
                <div class="md-form filter-date">
                    <input placeholder="To" type="text" id="date-picker-end" class="datepicker">
                    <label for="date-picker-end"></label>
                </div>
            </div>
        </div>
        <h2>Admin Dashboard</h2>
        <div class="table-responsive card">
            <table class="table table-hover" id="tendersTable">
                <thead>
                    <tr>
                        <th><span class="tbh-title">Tender Name</span></th>
                        <th><span class="tbh-title">Service</span></th>
                        <th><span class="tbh-title">Description</span></th>
                        <th><span class="tbh-title">Tender Doc</span></th>
                        <th><span class="tbh-title">Post Date</span></th>
                        <th><span class="tbh-title">Bids</span></th>
                    </tr>
                </thead>
                <tbody>
                  @if ($allTenders->count() > 0)
                    @each('pages.common.partials.dashboard_open_tenders_row', $allTenders, 'tender')
                  @else
                    <th colspan="6" class="text-center">No Tenders</th>
                  @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="table-container mt-2">
        <h2>Companies</h2>
        <div class="table-responsive card">
            <table class="table table-hover" id="companiesTable">
                <thead>
                    <tr>
                        <th><span class="tbh-title">User Name</span></th>
                        <th><span class="tbh-title">Company Name</span></th>
                        <th><span class="tbh-title">Company Type</span></th>
                        <th><span class="tbh-title">Status</span></th>
                    </tr>
                </thead>
                <tbody>
                  @if ($allUsers->count() > 0)
                    @each('pages.admin.partials.dashboard_user_row', $allUsers, 'user')
                  @else
                    <th colspan="4" class="text-center">No Companies</th>
                  @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('script')
<script>
  $(document).ready(function() {

    // Datatable for /admin/dashboard Tenders
    table = $('#tendersTable').DataTable({
      lengthChange: false,
      order: [],
      pageLength: 10
    });

    // Datatable for /admin/dashboard Tenders
    table2 = $('#companiesTable').DataTable({
      lengthChange: false,
      order: [],
      pageLength: 10
    });

    // Extend dataTables search
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var min  = $('#date-picker-start').val();
            var max  = $('#date-picker-end').val();
            var createdAt = data[4] || 0; // Our date column in the table

            if  (
                    ( min == "" || max == "" )
                    ||
                    ( moment(createdAt).isSameOrAfter(min) && moment(createdAt).isSameOrBefore(max) )
                )
            {
                return true;
            }
            return false;
        }
    );
  });

  $('.datepicker').change(function() {
    table.draw();
  });

</script>
@endsection

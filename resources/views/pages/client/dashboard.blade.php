@extends('layouts.default')

@section('page')
  <div id="page-client-dashboard">
    @parent
  </div>
@stop

@section('content')
  <div class="container">
    <ol class="breadcrumb mt-2">
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="table-container" id="dashboard-tenders-table">
      <h2>Tenders</h2>
      <div class="tenders-container last-container">
        <div class="tabs-wrapper">
          <ul class="nav classic-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link waves-light active" data-toggle="tab" href="#active_tenders" role="tab">Active ({{$openTenders->count()}})</a>
            </li>
            <li class="nav-item">
              <a class="nav-link waves-light" data-toggle="tab" href="#closed_tenders" role="tab">Closed ({{$closedTenders->count()}})</a>
            </li>
          </ul>
        </div>

        <div class="tab-content card">
          <div class="tab-pane fade in show active" id="active_tenders" role="tabpanel">
            <div class="table-container">

              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                  <tr>
                    <th><span class="tbh-title">Tender Name</span></th>
                    <th><span class="tbh-title">Service</span></th>
                    <th><span class="tbh-title">Description</span></th>
                    <th><span class="tbh-title">Post Date</span></th>
                    <th><span class="tbh-title">Bids</span></th>
                    <th><span class="tbh-title">Deadline</span></th>
                  </tr>
                  </thead>
                  <tbody>
                    @if ($openTenders->count() > 0)
                      @each('pages.common.partials.dashboard_open_tenders_row', $openTenders, 'tender')
                    @else
                    <tr>
                      <td colspan="6" class="text-center">No Active Tenders</td>
                    </tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="tab-pane fade" id="closed_tenders" role="tabpanel">
            <div class="table-container">

              <div class="table-responsive">
                <table class="table">
                  <thead>
                  <tr>
                    <th><span class="tbh-title">Tender Name</span></th>
                    <th><span class="tbh-title">Service</span></th>
                    <th><span class="tbh-title">Description</span></th>
                    <th><span class="tbh-title">Post Date</span></th>
                    <th><span class="tbh-title">Bids</span></th>
                    <th><span class="tbh-title">End Date</span></th>
                  </tr>
                  </thead>
                  <tbody>
                    @if ($closedTenders->count() > 0)
                      @each('pages.common.partials.dashboard_closed_tenders_row', $closedTenders, 'tender')
                    @else
                    <tr>
                      <td colspan="6" class="text-center">No Closed Tenders</td>
                    </tr>
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>

        <div class="add-tender-container text-center" style="width: 100%; margin-top: 2rem;">
          <a href="{{route('tender.create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Post Tender</a>
        </div>
      </div>

    </div>

    <h2>Contracts</h2>
    <div class="tenders-container last-container">
      <div class="tabs-wrapper">
        <ul class="nav classic-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link waves-light active" data-toggle="tab" href="#active_contracts" role="tab">Active ({{$openContracts->count()}})</a>
          </li>
          <li class="nav-item">
            <a class="nav-link waves-light" data-toggle="tab" href="#closed_contracts" role="tab">Ended ({{$endedContracts->count()}})</a>
          </li>
        </ul>
      </div>
      <div class="tab-content card">
        <div class="tab-pane fade in show active" id="active_contracts" role="tabpanel">
          <div class="table-container">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                <tr>
                  <th><span class="tbh-title">Tender Name</span></th>
                  <th><span class="tbh-title">Start Date</span></th>
                  <th><span class="tbh-title">Service</span></th>
                  <th><span class="tbh-title">Description</span></th>
                  <th><span class="tbh-title">Status</span></th>
                </tr>
                </thead>
                <tbody>
                  @if ($openContracts->count() > 0)
                    @each('pages.common.partials.dashboard_active_contracts_row', $openContracts, 'contract')
                  @else
                  <tr>
                    <td colspan="5" class="text-center">No Active Contracts</td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="closed_contracts" role="tabpanel">
          <div class="table-container">
            <div class="table-responsive">
              <table class="table">
                <thead>
                <tr>
                  <th><span class="tbh-title">Tender Name</span></th>
                  <th><span class="tbh-title">Post Date</span></th>
                  <th><span class="tbh-title">End Date</span></th>
                  <th><span class="tbh-title">Service</span></th>
                  <th><span class="tbh-title">Description</span></th>
                  <th>
                    <span class="tbh-title">Status</span>
                  </th>
                </tr>
                </thead>
                <tbody>
                  @if ($endedContracts->count() > 0)
                    @each('pages.common.partials.dashboard_ended_contracts_row', $endedContracts, 'contract')
                  @else
                  <tr colspan="6">
                    <td colspan="5" class="text-center">No Ended Contracts</td>
                  </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop
@section('script')
<script>
  $(document).ready(function() {

    // Datatable for /admin/dashboard Tenders
    table = $('table.table').DataTable({
      lengthChange: false,
      order: [],
      pageLength: 10
    });
  });
</script>
@endsection

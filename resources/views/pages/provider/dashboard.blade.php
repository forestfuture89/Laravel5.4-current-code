@extends('layouts.default')

@section('page')
  <div id="page-provider-dashboard">
    @parent
  </div>
@stop

@section('content')
  <div class="container">
    <ol class="breadcrumb mt-2">
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="table-container">
      <h2>Contracts</h2>
      <div class="tenders-container">
        <div class="tabs-wrapper">
          <ul class="nav classic-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link waves-light active" data-toggle="tab" href="#proposed_contracts" role="tab">Proposed ({{ count($proposedContracts) }})</a>
            </li>
            <li class="nav-item">
              <a class="nav-link waves-light" data-toggle="tab" href="#active_contracts" role="tab">Active ({{ count($activeContracts) }})</a>
            </li>
            <li class="nav-item">
              <a class="nav-link waves-light" data-toggle="tab" href="#ended_contracts" role="tab">Ended ({{ count($endedContracts) }})</a>
            </li>
          </ul>
        </div>
        <div class="tab-content card">
          <div class="tab-pane fade" id="active_contracts" role="tabpanel">
            <div class="table-container inc-photo-container">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                  <tr>
                    <th><span class="tbh-title">Tender Name</span></th>
                    <th><span class="tbh-title">Client</span></th>
                    <th><span class="tbh-title">Start Date</span></th>
                    <th><span class="tbh-title">Service</span></th>
                    <th><span class="tbh-title">Description</span></th>
                    <th><span class="tbh-title">Status</span></th>
                  </tr>
                  </thead>
                  <tbody>
                    @each('pages.common.partials.dashboard_active_contracts_row', $activeContracts, 'contract')
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="ended_contracts" role="tabpanel">
            <div class="table-container inc-photo-container">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                  <tr>
                    <th><span class="tbh-title">Tender Name</span></th>
                    <th><span class="tbh-title">Client</span></th>
                    <td><span class="tbh-title">Post Date</span></td>
                    <td><span class="tbh-title">End Date</span></td>
                    <th><span class="tbh-title">Service</span></th>
                    <th><span class="tbh-title">Description</span></th>
                    <th><span class="tbh-title">Status</span></th>
                  </tr>
                  </thead>
                  <tbody>
                    @each('pages.common.partials.dashboard_ended_contracts_row', $endedContracts, 'contract')
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="tab-pane fade  in show active" id="proposed_contracts" role="tabpanel">
            <div class="table-container inc-photo-container">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                  <tr>
                    <th><span class="tbh-title">Tender Name</span></th>
                    <th><span class="tbh-title">Client</span></th>
                    <th><span class="tbh-title">Service</span></th>
                    <th><span class="tbh-title">Description</span></th>
                    <th><span class="tbh-title">Post Date</span></th>
                    <th><span class="tbh-title">Deadline</span></th>
                  </tr>
                  </thead>
                  <tbody>
                    @each('pages.provider.partials.dashboard_proposed_contracts_row', $proposedContracts, 'contract')
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="table-container inc-photo-container" style="margin-top: 2rem">
        <h2>Tenders</h2>
        <div class="tenders-container last-container">
          <div class="tabs-wrapper">
            <ul class="nav classic-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link waves-light active" data-toggle="tab" href="#active_tenders" role="tab">Active ({{ $openTenders->count() }})</a>
              </li>
              <li class="nav-item">
                <a class="nav-link waves-light" data-toggle="tab" href="#closed_tenders" role="tab">Closed ({{ $closedTenders->count() }})</a>
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
                      <th><span class="tbh-title">Client</span></th>
                      <th><span class="tbh-title">Service</span></th>
                      <th><span class="tbh-title">Description</span></th>
                      <th><span class="tbh-title">Post Date</span></th>
                      <th><span class="tbh-title">Deadline</span></th>
                    </tr>
                    </thead>
                    <tbody>
                      @each('pages.common.partials.dashboard_open_tenders_row', $openTenders, 'tender')
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
                      <th><span class="tbh-title">Client</span></th>
                      <th><span class="tbh-title">Service</span></th>
                      <th><span class="tbh-title">Description</span></th>
                      <th><span class="tbh-title">Post Date</span></th>
                      <th><span class="tbh-title">End Date</span></th>
                    </tr>
                    </thead>
                    <tbody>
                      @each('pages.common.partials.dashboard_closed_tenders_row', $closedTenders, 'tender')
                    </tbody>
                  </table>
                </div>
              </div>
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
      bAutoWidth: false,
      pageLength: 10
    });
  });
</script>
@endsection

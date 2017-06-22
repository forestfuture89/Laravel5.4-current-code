@extends('layouts.default')

@section('page')
  <div id="page-client-view-tenders">
    @parent
  </div>
@stop

@section('content')
<div class="container">
    <ol class="breadcrumb mt-2">
      <li class="breadcrumb-item active"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item active">{{$tender->name}}</li>
    </ol>
    <div class="page-title-container">
        <div class="page-title">
            <h1>{{$tender->name}}</h1>
        </div>
    </div>
    <div class="tender-detail-container">
        <div class="row">
            <div class="col">
                <small>POST DATE</small>
                <h4>{{$tender->created_at->timezone('America/Chicago')->format('n/j/y')}}</h4>
            </div>
            <div class="col">
                <small>START DATE</small>
                <h4>{{$tender->expires_at->timezone('America/Chicago')->format('n/j/y')}}</h4>
            </div>
            <div class="col">
                <small>SERVICE TYPE</small>
                <h4>{{$tender->service}}</h4>
            </div>
        </div>



        <div class="description">
            <small>DESCRIPTION</small>
            <p>{{$tender->description}}</p>
        </div>

        <div class="doc-container">

            @if ($tender->documents()->where('type', 0)->count() == 1)
            <a href="{{route('document.download', ['id' =>$tender->documents()->where('type', 0)->first()->id])}}" class="btn btn-sm btn-primary"><i class="fa fa-file fa-2x"></i> Tender Doc</a>
            @elseif ($tender->documents()->where('type', 0)->count() > 1)
            <div class="btn-group">
                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file fa-2x"></i> Tender Docs</button>
                <div class="dropdown-menu">
                    @foreach ($tender->documents()->where('type', 0) as $document)
                    <a class="dropdown-item" href="{{route('document.download', ['id' =>$document->id])}}">Download Document #{{$loop->iteration}}</a>
                    @endforeach
                </div>
            </div>
            @endif

            @if ($tender->documents()->where('type', 2)->where('user_id', Auth::user()->id)->count() == 0)
              @if ($tender->documents()->where('type', 1)->count() == 1)
              <a href="{{ route('document.download', ['id' =>$tender->documents()->where('type', 1)->first()->id]) }}" class="btn btn-sm btn-primary"><i class="fa fa-copy fa-2x"></i> Bid Template</a>
              @endif
            @else
              <div class="btn-group">
                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file fa-2x"></i> Bid Docs</button>
                <div class="dropdown-menu">
                  @if ($tender->documents()->where('type', 1)->first())
                    <a class="dropdown-item" href="{{ route('document.download', ['id' =>$tender->documents()->where('type', 1)->first()->id]) }}"><i class="fa fa-copy fa-2x"></i> Bid Template</a>
                  @endif
                  @foreach ($tender->documents()->where('type', 2)->where('user_id', Auth::user()->id) as $document)
                    <a class="dropdown-item" href="{{ route('document.download', ['id' =>$document->id]) }}">Bid Doc #{{$loop->index + 1 }}</a>
                  @endforeach
                </div>
              </div>
            @endif

            @if ((Auth::user()->company()->type == 1 && $tender->createdBy()->company() == Auth::user()->company() ) || Auth::user()->company()->type == 0)
              @if ($tender->awarded_at == null && $tender->ended_at == null)
              <a class="btn btn-sm blue-grey" href="{{route('tender.edit', ['id' =>$tender->id])}}"><i class="fa fa-pencil fa-2x"></i> Edit Tender</a>
              <a class="btn btn-sm blue-grey" onclick="confirmModal('1')"><i class="fa fa-close fa-2x"></i> Close Tender</a>
              @elseif ($tender->awarded_at == null && $tender->ended_at != null)
              <a class="btn btn-sm blue-grey" href="{{route('tender.edit', ['id' =>$tender->id])}}"><i class="fa fa-check fa-2x"></i> Reopen Tender</a>
              @elseif ($tender->awarded_at != null && $tender->ended_at == null)
              <a class="btn btn-sm blue-grey" onclick="confirmModal('2')"><i class="fa fa-close fa-2x"></i> End Contract</a>
              @endif
            @endif

            @if (Auth::user()->company()->type == 2)
              @if ($bid_status == 'not_bid')
              <a class="btn btn-sm blue-grey" onclick="bidModal()">Submit Bid</a>
              @elseif ($bid_status == 'not_win')
              <a class="btn btn-sm blue-grey" onclick="bidModal()">Update Bid</a>
              <a class="btn btn-sm red" onclick="bidCancelModal()">Cancel Bid</a>
              @endif
            @endif

        </div>
    </div>
    @if ((Auth::user()->company()->type == 1 || Auth::user()->company()->type == 0) && $tender->createdBy()->company() == Auth::user()->company())
    <div class="bids-list-container">
        <h3>Bids</h3>
        <div class="table-responsive bids-list card">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><span class="tbh-title">Company</span></th>
                        <th><span class="tbh-title">Bid</span></th>
                        <th><span class="tbh-title">Supporting Docs</span></th>
                        <th><span class="tbh-title">Submit Date</span></th>
                        <th><span class="tbh-title">Action</span></th>
                    </tr>
                </thead>
                <tbody>
                  @if ($tender->bids()->count() > 0)
                    @each('pages.client.partials.tender_bid_list', $tender->bids, 'bid')
                  @else
                    <th colspan="5"><span class="tbh-title">No bids yet.</span></th>
                  @endif
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<!-- Award Modal -->
<div class="modal fade" id="award_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Body-->
            <div class="modal-body">
                <p>Close this tender and award the contract to:</p>
                <h3 id="award_modal_provider">Provider</h3>
            </div>
            <!--Footer-->
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                <form method="post" id="award_contract_form" action="{{route('tender.award')}}">
                  {{ csrf_field() }}
                  <input type="hidden" value="{{$tender->id}}" id="tender_id" name="tender_id">
                  <input type="hidden" value="0" id="bid_id" name="bid_id">
                  <button type="submit" class="btn btn-primary">Award Contract</button>
                </form>
            </div>
        </div>
        <!--/.Content-->
    </div>
</div>

<!-- Confirm Modal for closing Tender -->
<div class="modal fade" id="confirm_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Body-->
            <div class="modal-body">
              <h3>
              @if ($tender->awarded_at == null && $tender->ended_at == null)
              Are you sure you want to close this tender?
              @elseif ($tender->awarded_at != null && $tender->ended_at == null)
              Are you sure you want to end this contract?
              @endif
              </h3>
            </div>
            <!--Footer-->
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">Cancel</button>
                <form method="post" id="confirm_form" action="{{route('tender.change')}}">
                  {{ csrf_field() }}
                  <input type="hidden" value="{{$tender->id}}" id="tender_id" name="tender_id">
                  <input type="hidden" value="" id="type" name="type">
                  <button type="submit" class="btn btn-primary">
                    @if ($tender->awarded_at == null && $tender->ended_at == null)
                    Close Tender
                    @elseif ($tender->awarded_at != null && $tender->ended_at == null)
                    End Contract
                    @endif
                  </button>
                </form>
            </div>
        </div>
        <!--/.Content-->
    </div>
</div>

<!-- Bid Submit & Update Modal -->
@if (Auth::user()->company()->type == 2)
<div class="modal fade" id="bid_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Body-->
      <div class="modal-body">

        <form id="bid_form" method="POST" enctype="multipart/form-data"
              @if ($bid_status == 'not_bid')
              action="{{ route('tender.bid', ['id' =>$tender->id]) }}"
              @else
              action="{{ route('tender.bid.update', ['id' =>$tender->id]) }}"
              @endif
        >
          {{ csrf_field() }}

          <div class="md-form">
            <span>Job Name: {{ $tender->name }}</span>
          </div>
          <div class="md-form">
            <span>Deadline: {{$tender->deadline_at->timezone('America/Chicago')->format('n/j/y')}}</span>
          </div>
          <div class="md-form">
            <div class="file-field">
              <div class="btn btn-sm blue-grey lighten-1">
                <span>+ Bid Docs</span>
                <input type="file" id="bid_docs" multiple="multiple">
              </div>
              <div class="file-path-wrapper">
                <input class="file-path" id="bid_doc_name" type="text" placeholder="Upload your file" readonly>
              </div>
            </div>
          </div>
          <div class="md-form" id="file_chips" style="padding-top: 25px;">
            @if ($bid_status == 'not_win')
              <input type="hidden" id="deletedDocs_id_list" name="deletedDocs_id_list">
              @foreach ($bid_documents as $document)
                <div class="chip">
                  Bid Doc {{ $loop->index + 1 }}
                  <i class="close fa fa-times" style="position: inherit;" onclick="docDelete({{ $document->id }})"></i>
                </div>
              @endforeach
            @endif
          </div>
        </form>
      </div>
      <!--Footer-->
      <div class="modal-footer">
        <button type="button" class="btn btn-clear" data-dismiss="modal" onclick="window.location.reload();">Cancel</button>
        <button type="submit" id="bid_btn" class="btn btn-primary" form="bid_form" disabled>
          @if ($bid_status == 'not_bid')
            Submit Bid
          @else
            Update Bid
          @endif
        </button>
      </div>
    </div>
    <!--/.Content-->
  </div>
</div>
@endif

<!-- Bid Cancel Modal -->
<div class="modal fade" id="bid_cancel_modal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Header-->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title w-100" id="ModalLabel">
          Cancel Bid
        </h4>
      </div>
      <!--Body-->
      <div class="modal-body">
        <form id="bid_cancel_form" method="POST" action="{{ route('tender.bid.cancel', ['id' =>$tender->id]) }}">
          {{ csrf_field() }}

          <div class="md-form">
            <span>Are you sure you want to cancel this bid?</span>
          </div>
        </form>
      </div>
      <!--Footer-->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
        <button type="submit" id="bid_cancel_btn" class="btn btn-danger" form="bid_cancel_form">Cancel Bid</button>
      </div>
    </div>
    <!--/.Content-->
  </div>
</div>
@stop

@section('script')
<script>
    function openAwardModal (name, id) {
        $('#award_contract_form > #bid_id').val(id);
        $('#award_modal_provider').text(name);
        $('#award_modal').modal('show');
        return false;
    }
    function confirmModal (type) {
        $('#confirm_form > #type').val(type);
        $('#confirm_modal').modal('show');
        return false;
    }
    function bidModal () {
        $('#bid_modal').modal('show');
        return false;
    }
    function bidCancelModal () {
        $('#bid_cancel_modal').modal('show');
        return false;
    }

    var bidDoc_list = []; // set a global variable for uploaded multiple bid templates

    // check if the bid template file is selected
    $('#bid_docs').change(function() {
        if ($('#bid_docs').val().length > 0) {
            var doc = $(this).get(0).files[0];
            bidDoc_list.push(doc);

            $(this).before(
                $(this).clone(true)
                    .removeAttr("id")
                    .attr("name", "bid_docs[]")
                    .attr("chip_bind_number", bidDoc_list.lastIndexOf(doc))
            );

            var file_chip = '<div class="chip">' +
                            doc.name +
                            '<i class="close fa fa-times" style="position: inherit;" onclick="chipDelete(' + bidDoc_list.lastIndexOf(doc) + ')"></i>' +
                            '</div>';

            $('#file_chips').append(file_chip);
            $('#bid_btn').removeAttr('disabled');
        } else {
            $('#bid_btn').attr('disabled', 'disabled');
        }
    });

    // remove the selected one from the uploaded file array
    function chipDelete(index) {
        delete bidDoc_list[index];
        $('#bid_docs').parent().find('input[chip_bind_number="' + index + '"]').remove();

        var chip_size = $("#file_chips > .chip").length;
        if (chip_size == 1) {
            $("#bid_doc_name").val(null);
            $('#bid_btn').attr('disabled', 'disabled');
        }
    }

    // remove the selected one from the existing doc list
    function docDelete(docID) {
        var deletedDocs_id_list = new Array();
        if ($("#deletedDocs_id_list").val() != "") {
            deletedDocs_id_list = JSON.parse($("#deletedDocs_id_list").val());
        }
        deletedDocs_id_list.push(docID);
        $("#deletedDocs_id_list").val(JSON.stringify(deletedDocs_id_list));
        $('#bid_btn').removeAttr('disabled');

        var chip_size = $("#file_chips > .chip").length;
        if (chip_size == 1) {
            $("#bid_doc_name").val(null);
            $('#bid_btn').attr('disabled', 'disabled');
        }
    }
</script>
@endsection

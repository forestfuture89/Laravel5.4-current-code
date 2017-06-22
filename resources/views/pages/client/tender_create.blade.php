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
  <div id="page-c-add-tenders">
    @parent
  </div>
@stop

@section('content')
  <div class="container">
    <ol class="breadcrumb mt-2">
      <li class="breadcrumb-item active"><a href="{{route('client.dashboard')}}">Dashboard</a></li>
      <li class="breadcrumb-item active">Post Tender</li>
    </ol>
    <div class="form-container">
      <div class="card mt-4">
        <div class="form-header">
          <h3><i class="fa fa-ship" aria-hidden="true"></i> Post Tender</h3>
        </div>

        <div class="card-block">
          <form action="{{route('tender.store')}}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}

            <input type="hidden" name="user_id" value="{{Auth::user()->id}}">

            <div class="md-form">
              <input type="text" id="tender_name" name="tender_name"
                     class="form-control {{ ($errors->has('tender_name')) ? 'invalid' : '' }}"
                     value="{{ old('tender_name') }}" required autofocus>
              <label for="tender_name">Tender Name</label>
              @if ($errors->has('tender_name'))
                <p class="text-danger err-msg">{{ $errors->first('tender_name') }}</p>
              @endif
            </div>
            <div class="md-form">
              <input type="text" id="tender_desc" name="tender_desc"
                     class="form-control {{ ($errors->has('tender_desc')) ? 'invalid' : '' }}"
                     value="{{ old('tender_desc') }}" required>
              <label for="tender_desc">Description</label>
              @if ($errors->has('tender_desc'))
                <p class="text-danger err-msg">{{ $errors->first('tender_desc') }}</p>
              @endif
            </div>
            <div class="md-form">
              <input type="text" id="tender_service" name="tender_service"
                     class="form-control {{ ($errors->has('tender_service')) ? 'invalid' : '' }}"
                     value="{{ old('tender_service') }}" required>
              <label for="tender_service">Service Type</label>
              @if ($errors->has('tender_service'))
                <p class="text-danger err-msg">{{ $errors->first('tender_service') }}</p>
              @endif
            </div>
            <div class="md-form">
              <input type="hidden" id="tender_deadline_time" class="form-control timepicker" value="05:00PM">
              <input type="text" id="tender_deadline" name="tender_deadline"
                     class="form-control datepicker {{ ($errors->has('tender_deadline')) ? 'invalid' : '' }}"
                     value="{{ old('tender_deadline') }}" required>
              <label for="tender_deadline">Deadline (for Bids)</label>
              @if ($errors->has('tender_deadline'))
                <p class="text-danger err-msg">{{ $errors->first('tender_deadline') }}</p>
              @endif
            </div>
            <div class="md-form">
              <input type="hidden" id="tender_expires_time" class="form-control timepicker" value="05:00PM">
              <input type="text" id="tender_expires" name="tender_expires"
                     class="form-control datepicker {{ ($errors->has('tender_expires')) ? 'invalid' : '' }}"
                     value="{{ old('tender_expires') }}" required>
              <label for="tender_expires">Start date (for Contract)</label>
              @if ($errors->has('tender_expires'))
                <p class="text-danger err-msg">{{ $errors->first('tender_expires') }}</p>
              @endif
            </div>

            <div class="md-form">
              <div class="card">
                <div class="card-block">
                  <div class="card-text">
                    <div class="file-field">
                      <div class="btn btn-sm blue-grey lighten-1">
                        <span><i class="fa fa-upload" aria-hidden="true"></i> Tender Docs</span>
                        <input type="file" id="tender_docs[]" name="tender_docs[]" multiple="1" type="file">
                      </div>
                      <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Select one or more files">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="md-form">
              <div class="card">
                <div class="card-block">
                  <div class="card-text">
                    <div class="file-field">
                      <div class="btn btn-sm blue-grey lighten-1">
                        <span><i class="fa fa-upload" aria-hidden="true"></i> Bid Template</span>
                        <input type="file" name="bid_template">
                      </div>
                      <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Optionally include a template for providers to use">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="text-center submit-button">
              <button type="submit" class="btn btn-primary"><i class="fa fa-plus fa-2x" aria-hidden="true"></i> Post Tender</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
@stop

@section('script')
  <script>
      new Autogrow(document.getElementById('tender_desc'));
  </script>
@endsection

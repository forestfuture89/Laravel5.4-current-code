<tr>
    <td class="bid-item photo">
        <a href="{{ route('profile', ['id' => $bid->author()->id]) }}" class="bidder-photo">
            <img src="/public/profile_pics/avatar.jpg" alt="" style="max-width: 30px"/>
            <span class="bider-name">{{$bid->author()->company()->name}}</span>
        </a>
    </td>
    <td>
        <button href="{{route('document.download', ['id' =>$bid->bidDocument()->id])}}" class="btn btn-sm btn-primary" style="text-decoration: none"><i class="fa fa-download" aria-hidden="true"></i> Bid</button>
    </td>
    <td>
        @if ($bid->supportingDocuments()->count() == 0)
        <button class="btn btn-sm blue-grey" type="button" disabled="true"><i class="fa fa-folder-open" aria-hidden="true"></i> Supporting Docs</button>
        @elseif ($bid->supportingDocuments()->count() == 1)
        <button class="btn btn-sm blue-grey" href="{{route('document.download', ['id' =>$bid->supportingDocuments()->first()->id])}}" type="button"><i class="fa fa-download" aria-hidden="true"></i> Supporting Docs</button>
        @elseif ($bid->supportingDocuments()->count() > 1)
        <div class="btn-group">
            <button class="btn btn-sm blue-grey dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-download" aria-hidden="true"></i> Supporting Docs</button>
            <div class="dropdown-menu">
                @foreach ($bid->supportingDocuments() as $document)
                <a class="dropdown-item" href="{{route('document.download', ['id' =>$document->id])}}">Supporting Doc {{$loop->iteration}}</a>
                @endforeach
            </div>
        </div>
        @endif
    </td>
    <td>{{$bid->created_at->timezone('America/Chicago')->format('n/j/y')}}</td>
    <td>
        @if ($bid->winner == true)
          Contract Awarded
        @else
        <a href="#" class="award-contract" id="open_modal_award{{$bid->id}}" onclick="openAwardModal('{{$bid->author()->company()->name}}','{{$bid->id}}')">Award Contract</a>
        @endif
    </td>
</tr>

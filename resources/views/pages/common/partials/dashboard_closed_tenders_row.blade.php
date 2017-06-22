<tr>
  <td><a class="tbb-title" href="{{route('tender.show', ['id' =>$tender->id])}}">{{ $tender->name }}</a></td>

  @if (Auth::user()->company()->type == 2)
  <td class="bid-item photo">
    <a href="{{ route('profile', ['id' => $tender->createdBy()->id]) }}" class="bidder-photo">
      <img src="/{{ $tender->createdBy()->pic_path }}" alt=""/>
      <span class="bider-name">{{ $tender->createdBy()->name }}</span>
    </a>
  </td>
  @endif

  <td>{{ $tender->service }}</td>
  <td>{{ str_limit($tender->description, $limit = 50, $end = '...') }}</td>
  <td>{{ $tender->created_at->timezone('America/Chicago')->format('n/j/y') }}</td>

  @if (Auth::user()->company()->type == 1)
  <td>
    <a class="tbb-title" href="{{route('tender.show', ['id' =>$tender->id])}}">{{ $tender->bids->count() }} Bids</a>
  </td>
  @endif

  <td>{{ $tender->ended_at->timezone('America/Chicago')->format('n/j/y') }}</td>
</tr>

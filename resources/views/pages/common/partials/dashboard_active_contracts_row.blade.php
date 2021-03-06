<tr>
  <td><a class="tbb-title" href="{{route('tender.show', ['id' =>$contract->id])}}">{{ $contract->name }}</a></td>

  @if (Auth::user()->company()->type == 2)
  <td class="bid-item photo">
    <a href="{{ route('profile', ['id' => $contract->createdBy()->id]) }}" class="bidder-photo">
      <img src="/{{ $contract->createdBy()->pic_path }}" alt=""/>
      <span class="bider-name">{{ $contract->createdBy()->name }}</span>
    </a>
  </td>
  @endif

  <td>{{ $contract->expires_at->timezone('America/Chicago')->format('n/j/y') }}</td>
  <td>{{ $contract->service }}</td>
  <td>{{ str_limit($contract->description, $limit = 50, $end = '...') }}</td>

  <td>
    <span class="tbb-status">Contract awarded to <strong>{{ $contract->createdBy()->name }} Incorporated</strong> on {{ $contract->awarded_at->timezone('America/Chicago')->format('n/j/y') }}.</span>
    <a class="tbb-follow" href="{{route('tender.show', ['id' =>$contract->id])}}">View Contract</a>
  </td>
</tr>

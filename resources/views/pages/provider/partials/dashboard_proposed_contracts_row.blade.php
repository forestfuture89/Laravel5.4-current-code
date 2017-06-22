<tr>
  <td><a class="tbb-title" href="{{ route('tender.show', ['id' =>$contract->id]) }}">{{ $contract->name }}</a></td>
  <td class="bid-item photo">
    <a href="{{ route('profile', ['id' => $contract->createdBy()->id]) }}" class="bidder-photo">
      <img src="/{{ $contract->createdBy()->pic_path }}" alt=""/>
      <span class="bider-name">{{ $contract->createdBy()->name }}</span>
    </a>
  </td>
  <td>{{ $contract->service }}</td>
  <td>{{ $contract->description }}</td>
  <td>{{ $contract->created_at->timezone('America/Chicago')->format('n/j/y') }}</td>
  <td>{{ $contract->timeRemain() }}</td>
</tr>

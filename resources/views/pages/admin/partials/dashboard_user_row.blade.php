<tr>
    <td><a class="tbb-title" href="{{route('profile', ['id' => $user->id])}}">{{$user->name}}</a></td>
    <td>{{$user->company()->name}}</td>
    <td>
      @if ($user->company()->type == 0)
      OSX Administrator
      @elseif ($user->company()->type == 1)
      Client
      @elseif ($user->company()->type == 2)
      E&amp;P Operator
      @endif
    </td>
    <td><a class="tbb-title" href="{{route('profile', ['id' => $user->id])}}">
        @if ($user->blocked == 0)
        Active
        @elseif ($user->blocked == 1)
        Pending
        @endif
    </a></td>
</tr>

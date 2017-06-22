<div class="chip">
    <a href="{{route('document.download', ['id' =>$document->id])}}" title="link to doc">
      @if ($document->type == 0)
      Tender Document
      @elseif ($document->type == 1)
      Bid Template
      @endif
    </a>
    <i class="close fa fa-times" title="remove this doc" onclick="removeDoc('{{$document->id}}')"></i>
</div>


<script>
    @empty ($title)
        PNotify.{!! $type !!}({!! json_encode(['text' => $message]) !!});
    @else
        PNotify.{!! $type !!}({!! json_encode(['text' => $message, 'title' => $title]) !!});
    @endif
</script>

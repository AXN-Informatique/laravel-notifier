
<script>
    @empty ($title)
        PNotify.{!! $type === 'warning' ? 'notice' : $type !!}({!! json_encode(['text' => $message]) !!});
    @else
        PNotify.{!! $type === 'warning' ? 'notice' : $type !!}({!! json_encode(['text' => $message, 'title' => $title]) !!});
    @endif
</script>

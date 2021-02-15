
<script>
    @empty ($title)
        PNotify.alert({!!
            json_encode([
                'type' => $type === 'warning' ? 'notice' : $type,
                'text' => $message,
            ]) !!}
        );
    @else
        PNotify.alert({!!
            json_encode([
                'type' => $type === 'warning' ? 'notice' : $type,
                'title' => $title,
                'text' => $message,
            ]) !!}
        );
    @endif
</script>

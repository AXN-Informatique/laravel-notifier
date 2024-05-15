<script>
    jQuery(function(){
        @if (!empty($title))
        new PNotify({!! json_encode(['type' => $type, 'text' => $message, 'title' => $title]) !!});
        @else
        new PNotify({!! json_encode(['type' => $type, 'text' => $message]) !!});
        @endif
    });
</script>

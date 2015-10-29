
<script>
    new PNotify({
        type: '{{ $type }}',
        @if (!empty($title))
            title: '{{ $title }}',
        @endif
        text: '{{ $message }}'
    });
</script>

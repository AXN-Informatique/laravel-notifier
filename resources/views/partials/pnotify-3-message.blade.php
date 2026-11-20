<script>
    (function (show) {
        if (typeof jQuery === 'undefined' && document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', show);
        } else {
            show();
        }
    })(function () {
        jQuery(function(){
            @if (!empty($title))
            new PNotify({!! json_encode(['type' => $type, 'text' => $message, 'title' => $title]) !!});
            @else
            new PNotify({!! json_encode(['type' => $type, 'text' => $message]) !!});
            @endif
        });
    });
</script>

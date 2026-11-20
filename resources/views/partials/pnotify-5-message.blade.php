@php
    $PNotifyParams = [
        'type' => $type === 'warning' ? 'notice' : $type,
        'text' => $message,
    ];

    if ($title) {
        $PNotifyParams['title'] = $title;
    }

    $PNotifyParams = json_encode($PNotifyParams);
@endphp

<script>
    (function (show) {
        if (typeof PNotify === 'undefined' && document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', show);
        } else {
            show();
        }
    })(function () {
        PNotify.alert({!! $PNotifyParams !!});
    });
</script>

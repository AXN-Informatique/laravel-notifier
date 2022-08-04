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
    PNotify.alert({!! $PNotifyParams !!});
</script>

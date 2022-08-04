<p class="text-{!! ($type === 'error' ? 'danger' : $type) !!}">
    @if (! empty($title))
        <strong>{!! $title !!}</strong>&nbsp;
    @endif

    {!! $message !!}
</p>

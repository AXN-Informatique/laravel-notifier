<div class="alert alert-{!! ($type === 'error' ? 'danger' : $type) !!}" role="alert">
    @if (! empty($title))
        <strong>{!! $title !!}</strong><br>
    @endif

    {!! $message !!}
</div>

<div class="alert alert-{!! ($type === 'error' ? 'danger' : $type) !!}" role="alert">
    @if (! empty($title))
        <h4 class="alert-heading">{!! $title !!}</h4>
    @endif

    {!! $message !!}
</div>

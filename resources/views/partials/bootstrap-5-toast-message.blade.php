@php
    $color = $type === 'error' ? 'danger' : $type;

    switch ($type) {
        case 'success':
        case 'info':
            $aria = 'role="status" aria-live="polite" aria-atomic="true"';
            break;
        case 'warning':
            $aria = 'role="alert" aria-live="assertive" aria-atomic="true"';
            break;
        case 'error':
            $aria = 'role="alert" aria-live="assertive" aria-atomic="true"';
            $delay = $delay * $errorsCount;
            break;
    }
@endphp

<div id="{!! $id !!}" class="toast border-{!! $color !!}" {!! $aria !!} data-bs-delay="{!! $delay !!}">
    @if ($title)
        <div class="toast-header">
            <strong class="me-auto text-{!! $color !!}">
                {!! $title !!}
            </strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="{!! trans('comon::action.close') !!}"></button>
        </div>
        <div class="toast-body">
            {!! $message !!}
        </div>
    @else
        <div class="d-flex align-items-center">
            <div class="toast-body text-{!! $color !!}">
                {!! $message !!}
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    @endif
</div>

<script>
    new bootstrap.Toast(document.getElementById('{!! $id !!}')).show()
</script>

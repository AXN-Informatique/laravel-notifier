@php
    $color = $type === 'error' ? 'danger' : $type;

    switch ($type) {
        case 'info':
            $delay = 8000;
            $aria = 'role="status" aria-live="polite" aria-atomic="true"';
            break;
        case 'success':
            $delay = 5000;
            $aria = 'role="status" aria-live="polite" aria-atomic="true"';
            break;
        case 'warning':
            $delay = 10000;
            $aria = 'role="alert" aria-live="assertive" aria-atomic="true"';
            break;
        case 'error':
            $delay = $errorsCount ? 7500 * $errorsCount : 15000;
            $aria = 'role="alert" aria-live="assertive" aria-atomic="true"';
            break;
    }
@endphp

<div id="{!! $id !!}" class="toast border-{!! $color !!}" {!! $aria !!} data-delay="{!! $delay !!}">
    @if ($title)
        <div class="toast-header">
            <strong class="mr-auto text-{!! $color !!}">
                {!! $title !!}
            </strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="{!! trans('comon::action.close') !!}">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            {!! $message !!}
        </div>
    @else
        <div class="d-flex align-items-center">
            <div class="toast-body">
                {!! $message !!}
            </div>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="{!! trans('comon::action.close') !!}">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>

<script>
    $('#{!! $id !!}').toast()
</script>

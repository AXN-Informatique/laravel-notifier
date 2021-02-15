
<div class="alert alert-{!! ($type === 'error' ? 'danger' : $type) !!} alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center">
        @if ($type === 'success')
            <span class="fa-stack">
                <i class="fal fa-circle fa-stack-2x"></i>
                <i class="fal fa-check fa-stack-1x"></i>
            </span>
        @elseif ($type === 'info')
            <span class="fa-stack">
                <i class="fal fa-circle fa-stack-2x"></i>
                <i class="fal fa-info fa-stack-1x"></i>
            </span>
        @elseif ($type === 'warning')
            <i class="fal fa-exclamation-triangle fa-2x"></i>
        @elseif ($type === 'error')
            <i class="fal fa-ban fa-2x"></i>
        @endif

        @if (!empty($title))
            <strong>{{ $title }}</strong><br>
        @endif

        {!! $message !!}
    </div>

    <button type="button" class="close" data-dismiss="alert" aria-label="{!! trans('comon::action.close') !!}">
      <span aria-hidden="true">&times;</span>
    </button>
</div>

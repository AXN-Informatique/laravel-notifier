
<div class="alert alert-{!! ($type === 'error' ? 'danger' : $type) !!} fade in" role="alert">
    <div class="row">
        <div class="col-sm-1 hidden-xs">
            @if ($type === 'success')
                <span class="fa-stack">
                    <i class="fa fa-circle fa-stack-2x bg-success"></i>
                    <i class="fa fa-check fa-stack-1x text-success"></i>
                </span>
            @elseif ($type === 'info')
                <span class="fa-stack">
                    <i class="fa fa-circle fa-stack-2x"></i>
                    <i class="fa fa-info fa-stack-1x text-info"></i>
                </span>
            @elseif ($type === 'warning')
                <i class="fa fa-exclamation-triangle fa-2x"></i>
            @elseif ($type === 'error')
                <i class="fa fa-ban fa-2x"></i>
            @endif
        </div>
        <div class="col-sm-10">
            @if (!empty($title))
                <strong>{{ $title }}</strong><br>
            @endif

            {!! $message !!}
        </div>
        <div class="col-sm-1 hidden-xs">
            <button type="button" class="close" data-dismiss="alert" aria-label="Fermer"><span aria-hidden="true">&times;</span></button>
        </div>
    </div>
</div>

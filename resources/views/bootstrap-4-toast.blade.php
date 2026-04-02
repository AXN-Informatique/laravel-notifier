<div aria-live="polite" aria-atomic="true">
    <div class="toast-container" style="position: absolute; top: 0; right: 0; padding: 1rem;">
        @foreach ($flashMessages as $flashMessage)
            @include ('notifier::partials.bootstrap-4-toast-message', [
                'id' => $flashMessage['id'],
                'type' => $flashMessage['type'],
                'message' => $flashMessage['message'],
                'title' => $flashMessage['title'],
                'errorsCount' => $flashErrorsCount,
                'delay' => $flashMessage['delay'],
            ])
        @endforeach
        @foreach ($nowMessages as $nowMessage)
            @include ('notifier::partials.bootstrap-4-toast-message', [
                'id' => $nowMessage['id'],
                'type' => $nowMessage['type'],
                'message' => $nowMessage['message'],
                'title' => $nowMessage['title'],
                'errorsCount' => $nowErrorsCount,
                'delay' => $nowMessage['delay'],
            ])
        @endforeach
    </div>
</div>

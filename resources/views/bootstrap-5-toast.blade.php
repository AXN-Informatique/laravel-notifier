<div aria-live="polite" aria-atomic="true">
    <div class="toast-container position-absolute top-0 end-0 p-3">
        @foreach ($flashMessages as $flashMessage)
            @include ('notifier::partials.bootstrap-5-toast-message', [
                'id' => $flashMessage['id'],
                'type' => $flashMessage['type'],
                'message' => $flashMessage['message'],
                'title' => $flashMessage['title'],
                'errorsCount' => $flashErrorsCount,
            ])
        @endforeach
        @foreach ($nowMessages as $nowMessage)
            @include ('notifier::partials.bootstrap-5-toast-message', [
                'id' => $nowMessage['id'],
                'type' => $nowMessage['type'],
                'message' => $nowMessage['message'],
                'title' => $nowMessage['title'],
                'errorsCount' => $nowErrorsCount,
            ])
        @endforeach
    </div>
</div>

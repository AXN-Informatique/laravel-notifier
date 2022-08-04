<div>
    @foreach ($flashMessages as $flashMessage)
        @include ($viewName, [
            'id' => $flashMessage['id'],
            'type' => $flashMessage['type'],
            'message' => $flashMessage['message'],
            'title' => $flashMessage['title'],
            'errorsCount' => $errorsCount,
        ])
    @endforeach
    @foreach ($nowMessages as $nowMessage)
        @include ($viewName, [
            'id' => $nowMessage['id'],
            'type' => $nowMessage['type'],
            'message' => $nowMessage['message'],
            'title' => $nowMessage['title'],
            'errorsCount' => $errorsCount,
        ])
    @endforeach
</div>
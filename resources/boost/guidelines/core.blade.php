# Laravel Notifier

- Laravel Notifier provides a unified way to register and display flash/instant alert messages in Laravel web interfaces.
- Use the `notify()` helper to register messages: `notify()->success('message')` for flash (next request), `notify()->nowSuccess('message')` for instant (current request).
- Four message types are available: `info`, `success`, `warning`, `error`.
- Messages can be organized into independent stacks: `notify('my-stack')->success('message')`.
- Display messages with the `<x-notify />` Blade component. Predefined views include Bootstrap 4/5 (alerts, toasts), SweetAlert2, and PNotify 3/5.
- Always escape user data with `e()` in messages to prevent XSS: `notify()->success('Post '.e($post->title).' updated.')`.
- IMPORTANT: Activate `notifier-usage` skill for detailed usage patterns, component attributes, and template customization.

---
name: notifier-usage
description: "Activate when working with flash messages, instant notifications, alert display, the <x-notify /> component, notification stacks, or creating custom notification templates in a Laravel application using axn/laravel-notifier."
license: MIT
metadata:
  author: axn
---
# Laravel Notifier Usage

## Documentation

- See `docs/` directory for detailed patterns and documentation.
- See `README.md` for installation and basic usage.

## Registering Messages

### Flash messages (displayed after redirect)

```php
notify()->info('message', $title = null, $delay = 8000);
notify()->success('message', $title = null, $delay = 3000);
notify()->warning('message', $title = null, $delay = 8000);
notify()->error('message', $title = null, $delay = 8000);
```

### Instant messages (displayed in current request)

```php
notify()->nowInfo('message', $title = null, $delay = 8000);
notify()->nowSuccess('message', $title = null, $delay = 3000);
notify()->nowWarning('message', $title = null, $delay = 8000);
notify()->nowError('message', $title = null, $delay = 8000);
```

### Chaining and conditionals

```php
notify()
    ->success('Saved.')
    ->when($hasWarning, fn ($n) => $n->warning('Check this.'))
    ->unless($isValid, fn ($n) => $n->error('Invalid.'));
```

### Stacks

```php
notify()->success('message');                // default stack
notify('sidebar')->nowInfo('message');       // named stack
```

## Displaying Messages

### Basic component

```blade
<x-notify />
```

### Component attributes

| Attribute | Type | Default | Description |
|-----------|------|---------|-------------|
| `view-name` | string | config value | Blade template to use |
| `stack` | string | `default` | Message stack name |
| `:sort-by-type` | bool | `true` | Sort messages by type |
| `:group-by-type` | bool | `false` | Group same-type messages |
| `:without-flash-messages` | bool | `false` | Hide flash messages |
| `:without-now-messages` | bool | `false` | Hide instant messages |
| `:without-view-shared-errors` | bool | `false` | Ignore validation errors |

### Available templates

- `notifier::bootstrap-5`, `notifier::bootstrap-5-toast`, `notifier::bootstrap-5-alert`, `notifier::bootstrap-5-alert-advanced`
- `notifier::bootstrap-4`, `notifier::bootstrap-4-toast`, `notifier::bootstrap-4-alert`, `notifier::bootstrap-4-alert-advanced`
- `notifier::sweetalert2` (forces group-by-type, single modal only)
- `notifier::pnotify-5`, `notifier::pnotify-3`

## Custom Templates

Create a Blade view with access to `$flashMessages`, `$nowMessages`, `$flashErrorsCount`, `$nowErrorsCount`.

Each message is an array with keys: `id`, `type`, `message`, `title`, `delay`, `type_order`.

Use the generic partial to avoid rewriting loops:

```blade
@@include('notifier::partials.a-generic-component', [
    'viewName' => 'components.partials.my-message',
])
```

## Common Pitfalls

- **XSS**: `$message` and `$title` are NOT escaped in templates. Always use `e()` for user data.
- **SweetAlert2**: Can only display one modal at a time — messages are automatically grouped by type.
- **Bootstrap 5/4 simple views**: Do not support `group-by-type` (forced to `false`).
- **View shared errors**: Validation errors are automatically added to the default stack as instant messages. Use `:without-view-shared-errors="true"` to disable.

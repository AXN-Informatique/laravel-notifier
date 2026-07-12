# Laravel Notifier

- Unified flash/instant alert messages: `notify()->success('...')` (next request) / `notify()->nowSuccess('...')` (current request); display with `<x-notify />`; four types (`info`, `success`, `warning`, `error`) and independent stacks.
- SECURITY: always escape user data with `e()` in messages to prevent XSS.
- IMPORTANT: Activate `notifier-usage` skill for detailed usage patterns, component attributes and template customization.

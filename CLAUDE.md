# Laravel Notifier

Package Laravel pour l'enregistrement et l'affichage uniformisés de messages/alertes dans les interfaces web.

## Architecture

- `src/Notify.php` — Classe principale, utilise les traits `HasFlashMessages`, `HasNowMessages`, `CanGroupMessagesByType`
- `src/helpers.php` — Helper `notify()` pour accéder au service
- `src/View/Components/NotifyComponent.php` — Composant Blade `<x-notify />`
- `config/notifier.php` — Configuration (vue par défaut, tri, groupement)
- `resources/views/` — Templates prédéfinis (Bootstrap 4/5, SweetAlert2, PNotify 3/5)

## Concepts clés

- **Messages flash** : stockés en session, affichés à la requête suivante (après redirection)
- **Messages instantanés (now)** : affichés dans la requête courante
- **Stacks** : permet d'avoir plusieurs piles de messages indépendantes
- **Types** : info, success, warning, error

## Dépendances

- PHP 8.4+
- Laravel 12+

## Laravel Boost Assets

The package provides Laravel Boost integration assets in `resources/boost/`:
- **Guidelines** (`guidelines/core.blade.php`): Package overview for AI assistants
- **Skill** (`skills/notifier-usage/SKILL.md`): Detailed usage patterns, component attributes, templates, and customization

**Important:** These files must be kept up to date when components, configuration keys, or usage patterns change.

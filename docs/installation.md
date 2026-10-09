---
title: Installation et configuration
order: 1
---

Installation et configuration
=============================

Installation
------------

```bash
composer require axn/laravel-notifier
```

Le service provider est auto-découvert par Laravel.

Configuration
-------------

Publier le fichier de configuration :

```bash
php artisan vendor:publish --tag="notifier-config"
```

La commande crée un fichier `config/notifier.php` vide : n'y ajouter que les valeurs à modifier, les autres sont fusionnées depuis la configuration du package (`vendor/axn/laravel-notifier/config/notifier.php`).

```php
return [
    'default_view' => 'notifier::bootstrap-5-toast',
];
```

### Options disponibles

| Option | Type | Défaut | Description |
|--------|------|--------|-------------|
| `default_view` | string | `notifier::sweetalert2` | Template Blade par défaut |
| `sort_by_type` | bool | `true` | Trier les messages par type |
| `sort_type_order` | array | `[error, warning, success, info]` | Ordre d'affichage des types |
| `group_by_type` | bool | `false` | Grouper les messages du même type |
| `group_messages_format` | string | `<ul class="list-unstyled mb-0">%s</ul>` | Élément HTML qui enveloppe les messages groupés |
| `group_title_format` | string | `<strong>%s&nbsp;:&nbsp;</strong>` | HTML du titre d'un message groupé |
| `group_message_format` | string | `<li>%s%s</li>` | HTML d'un message groupé : titre puis message |

Les trois formats sont des masques `sprintf()`.

Publication des vues
--------------------

```bash
php artisan vendor:publish --tag="notifier-views"
```

Les vues seront copiées dans `resources/views/vendor/notifier`.

**Astuce** : ne remplacer que les vues personnalisées, les autres seront chargées depuis le package.

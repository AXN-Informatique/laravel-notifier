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

Le fichier sera copié dans `config/notifier.php`.

**Astuce** : ne mettre dans ce fichier que les valeurs modifiées, le reste sera fusionné depuis le package.

### Options disponibles

| Option | Type | Défaut | Description |
|--------|------|--------|-------------|
| `default_view` | string | `notifier::sweetalert2` | Template Blade par défaut |
| `sort_by_type` | bool | `true` | Trier les messages par type |
| `sort_type_order` | array | `[error, warning, success, info]` | Ordre d'affichage des types |
| `group_by_type` | bool | `false` | Grouper les messages du même type |
| `group_messages_format` | string | `<ul>...</ul>` | Format HTML des messages groupés |
| `group_title_format` | string | `<strong>...</strong>` | Format HTML du titre groupé |
| `group_message_format` | string | `<li>...</li>` | Format HTML d'un message groupé |

Publication des vues
--------------------

```bash
php artisan vendor:publish --tag="notifier-views"
```

Les vues seront copiées dans `resources/views/vendor/notifier`.

**Astuce** : ne remplacer que les vues personnalisées, les autres seront chargées depuis le package.

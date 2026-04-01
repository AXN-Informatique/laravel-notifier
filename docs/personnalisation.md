---
title: Personnalisation
order: 5
---

Personnalisation
================

Créer un template personnalisé
------------------------------

### 1. Créer la vue du composant

Créer un fichier Blade, par exemple `resources/views/components/my-notify.blade.php`.

### 2. L'utiliser

```blade
<x-notify view-name="components.my-notify" />
```

Ou le définir comme vue par défaut dans `config/notifier.php`.

### 3. Variables disponibles

| Variable | Type | Description |
|----------|------|-------------|
| `$flashMessages` | Collection | Messages flash |
| `$nowMessages` | Collection | Messages instantanés |
| `$flashErrorsCount` | int | Nombre d'erreurs flash |
| `$nowErrorsCount` | int | Nombre d'erreurs instantanées |

### 4. Structure d'un message

Chaque message est un tableau :

```php
[
    'id'         => 'notify-flash-default-1',  // identifiant unique
    'type'       => 'success',                 // info, success, warning, error
    'message'    => 'Contenu du message',
    'title'      => 'Titre optionnel',
    'delay'      => 3000,                      // durée d'affichage (ms)
    'type_order' => 2,                         // ordre de tri par type
]
```

### 5. Boucler sur les messages

```blade
<div>
    @foreach ($flashMessages as $flashMessage)
        @include('components.partials.my-notify-message', [
            'id' => $flashMessage['id'],
            'type' => $flashMessage['type'],
            'message' => $flashMessage['message'],
            'title' => $flashMessage['title'],
            'delay' => $flashMessage['delay'],
            'errorsCount' => $flashErrorsCount,
        ])
    @endforeach
    @foreach ($nowMessages as $nowMessage)
        @include('components.partials.my-notify-message', [
            'id' => $nowMessage['id'],
            'type' => $nowMessage['type'],
            'message' => $nowMessage['message'],
            'title' => $nowMessage['title'],
            'delay' => $nowMessage['delay'],
            'errorsCount' => $nowErrorsCount,
        ])
    @endforeach
</div>
```

### 6. Utiliser le composant générique

Pour éviter de réécrire les boucles, utiliser la partial générique du package :

```blade
@include('notifier::partials.a-generic-component', [
    'viewName' => 'components.partials.my-notify-message',
])
```

Il ne reste plus qu'à implémenter `components/partials/my-notify-message.blade.php`.

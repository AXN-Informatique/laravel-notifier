Mise à jours
============

De la version 3.x à la version 4.x
-----------------------------------

### Pré-requis

Afin de suivre l'évolutions de "l'écosystème", ce package requière maintenant au minimum **PHP 8** et **Laravel 8**.

Pour installer cette nouvelle version vous devez mettre à jours votre application en conséquence.

Dans le fichier `composer.json` vous devez remplacer :

```js
        "axn/laravel-adminlte3-template": "^X.0",
        "axn/laravel-notifier": "^X.0",
```

Par :

```js
        "axn/laravel-adminlte3-template": "^5.0",
        "axn/laravel-notifier": "^4.0",
```

Avec la mise à jour des packages `axn/laravel-notifier` **4.0** et `axn/laravel-adminlte3-template` **5.0**, tous les autres packages qui utilisent ceux-ci et votre application doivent êtres mis à jour pour utiliser les mêmes versions.

### Appel des notifications

Dans la très grande majoritée de nos applications (si ce n'est toutes) les notifications sont appellées dans les fichiers layout. Généralement de cette façon :

Pour la version 3 :
```blade
{!! notify()->showFlash() !!}

@if (isset($errors) && !$errors->isEmpty())
    {!! notify()->showError(implode('<br>', $errors->all())) !!}
@endif
```

Pour les versions 1 et 2 :
```blade
{!! Notifier::showFlash('notifier::pnotify') !!}

@if (isset($errors) && !$errors->isEmpty()) {!!
    Notifier::showError('notifier::pnotify', implode('<br>', $errors->all())) !!}
@endif
```

Et remplacer par le component :

```blade
<x-notify />
```

Avec éventuellement tous les attributs dont vous avez besoin (voir le readme).

### Suppression de la façade

Partout où vous avez utilisé la façade supprimée vous devez réaliser les deux opérations suivantes.

Remplacer :

- `Notifier::success(` par `notify()->success(`
- `Notifier::error(` par `notify()->error(`
- etc.

Remplacer également :

- `Notifier::showSuccess(` par `notify()->NowSuccess(`
- `Notifier::showError(` par `notify()->NowError(`

A noter que les méthodes `show*` étaient appellées dans les vues, elles doivent maintenant êtres appellées dans l'application. Mais à ma connaissance elles n'ont jamais été utilisées.

Enfin, supprimer l'appel : `use Axn\LaravelNotifier\Facade as Notifier;` ; ou utiliser PHP CS Fixer à la fin qui le fera pour vous.

### Suppression des helpers

Partout où vous avez utilisé les helpers supprimés vous devez les remplacer :

  - `notifier()` par `notify()`
  - `notify_info()` par `notify()->info()`
  - `notify_success()` par `notify()->success()`
  - `notify_warning()` par `notify()->warning()`
  - `notify_error()` par `notify()->error()`

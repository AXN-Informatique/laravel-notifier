# Laravel Notifier

Ce package vise à simplifier l'utilisation des notifications dans les interfaces web d'une application Laravel.


## Installation

Inclure le package avec Composer :

```sh
composer require axn/laravel-notifier
```

In Laravel 5.5 the service provider will automatically get registered.
In older versions of the framework just add the service provider
to the array of providers in `config/app.php`:

```php
// config/app.php

'provider' => [
    //...
    Axn\LaravelNotifier\ServiceProvider::class,
    //...
];
```

In Laravel 5.5 the facade will automatically get registered.
In older versions of the framework just add the facade
to the array of aliases in `config/app.php`:

```php
// config/app.php

'aliases' => [
    //...
    'Notifier' => Axn\LaravelNotifier\Facade::class,
    //...
];
```

## Utilisation

### Notifications flash

Pour ajouter des notifications flash, utilisez les fonctions suivantes :

- `info('message')`
- `success('message')`
- `warning('message')`
- `error('message')`

Soit via le helper `notifier()` :

```php
notifier()->info('message');

notifier()->success('message');

notifier()->warning('message');

notifier()->error('message');
```

Soit via la façade :

```php
use Axn\LaravelNotifier\Facade as Notifier;

//...

Notifier::info('message');

Notifier::success('message');

Notifier::warning('message');

Notifier::error('message');
```

Et pour afficher ces notifications flash dans vos views Blade, utilisez la fonction `showFlash()` :

```blade
notifier()->showFlash('notifier::bootstrap3');
```

ou

```blade
Notifier::showFlash('notifier::bootstrap3');
```

L'argument de cette fonction est le template de vue à utiliser pour effectuer le rendu de la notification.


### Notifications instantanées

Si vous souhaitez afficher directement des notifications, utilisez les fonctions suivantes :

- `showInfo('view', 'message')`
- `showSuccess('view', 'message')`
- `showWarning('view', 'message')`
- `showError('view', 'message')`

Soit via le helper `notifier()` :

```php
notifier()->showInfo('notifier::bootstrap3', 'message');

notifier()->showSuccess('notifier::bootstrap3', 'message');

notifier()->showWarning('notifier::bootstrap3', 'message');

notifier()->showError('notifier::bootstrap3', 'message');
```

Soit via la façade :

```php
Notifier::showInfo('notifier::bootstrap3', 'message');

Notifier::showSuccess('notifier::bootstrap3', 'message');

Notifier::showWarning('notifier::bootstrap3', 'message');

Notifier::showError('notifier::bootstrap3', 'message');
```

Toutes les méthodes `show*` prennent en premier paramètre le template de vue à utiliser
pour effectuer le rendu de la notification.


## Templates de vues disponibles

Il y a actuellement les templates de vue fournis par le package :

- bootstrap3 *(requiert le framework CSS Bootstrap 3)*
- bootstrap3-advanced *(requiert le framework CSS Bootstrap 3)*
- pnotify *(requiert le plugin JS PNotify 3)*
- pnotify4 *(requiert le plugin JS PNotify 4)*

Selon le template utilisé des installations de dépendances peuvent êtres nécessaires.

Par exemple pour les vues "Bootstrap3" il est nécessaire que vous ayez dans votre projet ce dernier.

Pareil pour les vues qui utilisent pnotify ; celui-ci doit être configuré dans votre projet selon sa version.


### Personnalisation des templates

Copier les fichiers de vues du package vers le dossier de vues de l'application via la commande :

```sh
php artisan vendor:publish
```

Et y effectuer les modification souhaitées.

Vous pouvez aussi créer vos propres templates. Exemple :

```php
Notifier::showFlash('nom-de-la-vue');
```

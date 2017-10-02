# Laravel Notifier

Ce package simplifie l'utilisation des notifications dans Laravel.

* [Installation](#markdown-header-installation)
* [Utilisation](#markdown-header-utilisation)
* [Personnalisation des templates](#markdown-header-personnalisation-des-templates)
* [changelog](changelog.md) :arrow_upper_right:


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

Pour ajouter des notifications flash, utiliser les méthodes suivantes :

```php
Notifier::success("Un message");

Notifier::info("Un message");

Notifier::warning("Un message");

Notifier::error("Un message");
```

Et pour afficher ces notifications flash :

```php
Notifier::showFlash('notifier::bootstrap3');
```

Si vous souhaitez afficher directement des notifications :

```php
Notifier::showSuccess('notifier::bootstrap3', "Un message");

Notifier::showInfo('notifier::bootstrap3', "Un message");

Notifier::showWarning('notifier::bootstrap3', "Un message");

Notifier::showError('notifier::bootstrap3', "Un message");
```

Toutes les méthodes `show*` prennent en premier paramètre le template de vue à utiliser
pour effectuer le rendu de la notification. Il y a actuellement trois templates fournis :

- bootstrap3 *(requiert le framework CSS Bootstrap 3)*
- bootstrap3-advanced *(requiert le framework CSS Bootstrap 3)*
- pnotify *(requiert le plugin JS PNotify)*

## Personnalisation des templates

Copier les fichiers de vues du package vers le dossier de vues de l'application via la commande :

```sh
php artisan vendor:publish
```

Et y effectuer les modification souhaitées.

Vous pouvez aussi créer vos propres templates. Exemple :

```php
Notifier::showFlash('nom-de-la-vue');
```

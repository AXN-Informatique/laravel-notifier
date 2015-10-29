# Laravel Notifier

Ce package simplifie l'utilisation des notifications dans Laravel.

## Installation

Inclure le package avec Composer :

```
composer require axn/laravel-notifier
```

Ajouter le service provider au tableau des providers dans `config/app.php` :

```
'Axn\LaravelNotifier\ServiceProvider',
```

Ajouter la façade au tableau des façades dans `config/app.php` :

```
'Notifier' => 'Axn\LaravelNotifier\Facade',
```

## Utilisation

Pour ajouter une notification flash :

```
Notifier::success("Un message");

Notifier::info("Un message");

Notifier::warning("Un message");

Notifier::error("Un message");
```

Et pour l'afficher :

```
Notifier::showFlash('notifier::pnotify');
```

Si vous souhaitez directement afficher une notification :

```
Notifier::showSuccess('notifier::pnotify', "Un message");

Notifier::showInfo('notifier::pnotify', "Un message");

Notifier::showWarning('notifier::pnotify', "Un message");

Notifier::showError('notifier::pnotify', "Un message");
```

## Personnalisation

Si personnalisation souhaitée, copier les fichiers de vues du package vers le dossier
de vues de l'application via la commande :

```
php artisan vendor:publish
```

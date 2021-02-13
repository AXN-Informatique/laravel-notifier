# Laravel Notifier

Ce package vise à simplifier l'enregistrement et l'affichage des notifications/alertes dans les interfaces web d'une application Laravel.

**Attention** à ne pas confondre dans Laravel les "[Notifications](https://laravel.com/docs/8.x/notifications)" et les "[Session Flash Data](https://laravel.com/docs/8.x/session#flash-data)" ; ce package utilisent ces dernières et n'a absolument rien à voir aux premières.


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

## Configuration

Ce package fournit un fichier de configuration.

*Consultez ce fichier pour voir ce que vous pouvez modifier, chacunes des options est documentée.*

Les valeurs de ce fichier sont accessibles de cette façon : `config('notifier.option')` où `option` est la clé du tableau de configuration.

Afin de personnaliser la configuration, vous devez publier le fichier dans votre application en exécutant la commande suivante :

```sh
php artisan vendor:publish --provider="Axn\News\ServiceProvider" --tag="config"
```

Le fichier sera alors copié dans `config/notifier.php` et automatiquement chargé par l'application.

**Astuce :** *ne mettez dans ce fichiers que ce que vous modifiez, le reste sera fusionné depuis le package.*


## Utilisation

### Notifications flash

Les notifications flash sont enregistrées en session et disponibles **pour la requêtte HTTP suivante**.

Typiquement elles permettent d'afficher des confirmations ou des erreurs après soumission d'un formulaire.

Cela correspond aux "Flash Data" de Laravel, mais en typant les messages afin de piloter plus facilement le rendu à l'écran.

Pour ajouter des notifications flash, utilisez au choix :

Soit via le helper `notify()` (ou son alisa `notifier()`) :

```php
notify()->info('message');

notify()->success('message');

notify()->warning('message');

notify()->error('message');
```

Soit les fonctions helpers raccourcies suivantes :

```php

`notify_info('message')`

`notify_success('message')`

`notify_warning('message')`

`notify_error('message')`
```

Soit via la façade (façon "historique" de ce package avant la version 2.9) :

```php
use Axn\LaravelNotifier\Facade as Notifier;

//...

Notifier::info('message');

Notifier::success('message');

Notifier::warning('message');

Notifier::error('message');
```

#### Affichage

Et pour afficher ces notifications flash dans vos views Blade, utilisez la fonction `showFlash()` :

```blade
notify()->showFlash();
```

ou via la façade :

```blade
Notifier::showFlash();
```

Cet appel est à placer selon le template de vue utilisé.

Par exemple avec un template Bootstrap vous allez le placer là où vous voulez l'afficher ; par contre avec un template PNotify vous allez le placer dans les scripts.

Par défaut le template de vue utilisé celui du fichier de configuration mais vous pouvez choisir la vue à utiliser pour effectuer le rendu de la notification.

```blade
notify()->showFlash('notifier::bootstrap3');
```

ou via la façade :

```blade
Notifier::showFlash('notifier::bootstrap3');
```

### Notifications instantanées

Si vous souhaitez afficher directement des notifications (non flash, donc dans la requête courante), utilisez les fonctions `show*` :

- `showInfo('view', 'message')`
- `showSuccess('view', 'message')`
- `showWarning('view', 'message')`
- `showError('view', 'message')`

Soit via le helper `notify()` (ou son alisa `notifier()`) :

```php
notify()->showInfo('notifier::bootstrap3', 'message');

notify()->showSuccess('notifier::bootstrap3', 'message');

notify()->showWarning('notifier::bootstrap3', 'message');

notify()->showError('notifier::bootstrap3', 'message');
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


## Templates de vues

Il y a actuellement les templates de vue fournis par le package :

- bootstrap3 *(requiert Bootstrap 3)* **deprecated**
- bootstrap3-advanced *(requiert Bootstrap 3)* **deprecated**
- bootstrap4 *(requiert Bootstrap 4)*
- bootstrap4-advanced *(requiert Bootstrap 4)*
- pnotify *(requiert PNotify 3)* **deprecated**
- pnotify4 *(requiert PNotify 4)* **deprecated**

- pnotify5 *(requiert PNotify 5)* **incomming**
- sweetalert2 *(requiert SweetAlert 2)* **incomming**

Selon le template utilisé des installations de dépendances peuvent êtres nécessaires.

Par exemple pour les vues "Bootstrap4" il est nécessaire que vous ayez dans votre projet ce dernier.

Pareil pour les vues qui utilisent pnotify ; celui-ci doit être configuré dans votre projet selon sa version.


### Personnalisation des templates disponibles

Copier les fichiers de vues du package vers le dossier de vues de l'application via la commande :

```sh
php artisan vendor:publish
```

Et y effectuer les modification souhaitées.


### Création de templates personnalisés

Vous pouvez aussi créer vos propres templates. Exemple :

```php
Notifier::showFlash('nom-de-la-vue');
```

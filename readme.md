# Laravel Notifier

Ce package simplifie l'utilisation des notifications dans Laravel.

* [Installation](#markdown-header-installation)
* [Utilisation](#markdown-header-utilisation)
* [Personnalisation des templates](#markdown-header-personnalisation-des-templates)
* [changelog](changelog.md) :arrow_upper_right:


## Installation

Requérir ce paquet dans votre composer.json :

```
    "require" : {
        "axn/laravel-notifier" : "~1.0.0"
    }
```

Ajouter le dépôt privé à votre composer.json :

```
    "repositories" : [{
            "type" : "vcs",
            "url" : "git@bitbucket.org:axn/laravel-notifier.git"
        }
    ]
```

Vous aurez besoin d'une clé SSH pour exécuter la commande suivante :

```
composer update
```

Après la mise à jour composer, ajouter le service provider au tableau des providers dans `config/app.php` :

```
'Axn\LaravelNotifier\ServiceProvider',
```

Ajouter la façade au tableau des façades dans `config/app.php` :

```
'Notifier' => 'Axn\LaravelNotifier\Facade',
```

## Utilisation

Pour ajouter des notifications flash, utiliser les méthodes suivantes :

```
Notifier::success("Un message");

Notifier::info("Un message");

Notifier::warning("Un message");

Notifier::error("Un message");
```

Et pour afficher ces notifications flash :

```
Notifier::showFlash('notifier::bootstrap3');
```

Si vous souhaitez afficher directement des notifications :

```
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

```
php artisan vendor:publish
```

Et y effectuer les modification souhaitées.

Vous pouvez aussi créer vos propres templates. Exemple :

```
Notifier::showFlash('nom-de-la-vue');
```

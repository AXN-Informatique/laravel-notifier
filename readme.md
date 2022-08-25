Laravel Notifier
================

Ce package vise à uniformiser, simplifier l'enregistrement et l'affichage des messages dans les interfaces web d'une application Laravel.

Cela permet d'utiliser les notifications/alertes sur de multiples projets sans avoir à implémenter à chaque fois les mêmes routines.

**Attention** à ne pas confondre dans Laravel les "[Notifications](https://laravel.com/docs/notifications)" et les "[Session Flash Data](https://laravel.com/docs/session#flash-data)" ; ce package utilise ces dernières et n'a absolument rien à voir aux premières.

Il y a deux possibilités d’utiliser ce package pour déclarer et afficher des messages :

- Soit pour les messages "flash" qui sont affichés dans la requête HTTP **suivante**
- Soit pour les messages "instantanés" qui sont affichés dans la requête HTTP **courante**

Les messages peuvent êtres de quatre types différents :

- info
- success
- warning
- error


Mises à jour
------------

Pour les instructions de mises à jour veuillez consulter le fichier `upgrade.md`.


Installation
------------

Inclure le package avec Composer :

```sh
composer require axn/laravel-notifier
```


Déclaration des messages
------------------------

Le point d'entré est le helper `notify()` qui retourne une instance de `Axn\Notifier\Notify`.

### Les messages "flash"

Typiquement ils permettent d'afficher des confirmations après soumission d'un formulaire (et donc après redirection).

Cela correspond aux "Flash Data" de Laravel, mais en *typant* les messages afin de piloter plus facilement le rendu à l'écran.

Pour ajouter des messages flash, utilisez :

```php
notify()->info('message');

notify()->success('message');

notify()->warning('message');

notify()->error('message');
```

Par exemple :

```php
class function PostController()
{
    public function update(Post $post)
    {
        //...
        $post->update([/*...*/]);

        notify()->success('Post '.e($post->title).' successfully updated.');

        return back();
    }
}
```

### Les messages "instantanés"

Ils permettent d'afficher un message pour transmettre une information directement sur l'écran sans redirection préalable.

Pour cela vous pouvez utiliser les fonctions `now*` :

```php
notify()->nowInfo('message');

notify()->nowSuccess('message');

notify()->nowWarning('message');

notify()->nowError('message');
```

Par exemple :

```php
class function PostController()
{
    public function edit(Post $post)
    {
        //...
        notify()->nowInfo('Editing '.e($post->title).' post.');

        return view('post.edit');
    }
}
```

### Les titres

Il est possible d'ajouter un second argument `$title` :

```php
notify()->success('Post '.e($post->title).' successfully updated.', 'Success');

notify()->nowInfo('Editing '.e($post->title).' post.', 'Information');
```

### Sécurité et prévention des attaques XSS

Afin de permettre de mettre du HTML dans les messages, les variables `$message` et `$title` ne sont **PAS échapées** dans les templates.

Si vous devez mettre des données en provenance de la base de données ou saisies par les utilisateurs vous devez les échapper comme dans les exemples ci-dessus avec le helper `e($string)`.

Sans cela c'est une faille de sécurité XSS.

**Note :** par contre les caractères `'` et `"` sont remplacés par `&apos;` et `&quot;`

### Durée d'affichage

Certain template ont une durée d'affichage avant de disparaitre.

SI vous souhaitez modifier cette durée d'affichage pour un message, par exemple parce qu'il est long.

Il est possible d'ajouter un troisième argument `$delay` :

```php
notify()->success('Post '.e($post->title).' successfully updated.', 'Success', 5000);

notify()->nowInfo('Editing '.e($post->title).' post.', 'Information', 15000);
```

Cet argument est en millisecondes, par défaut :

- info : 10000 (10s)
- success : 5000 (5s)
- warning : 12000 (12s)
- error : 15000 (15s)

A noter que sur les templates fournis par le package le temps d'affichage des erreurs sera multiplié par le nombre d'erreurs.


### Multiples messages et conditionnels

Vous pouvez ajouter plusieurs messages à la suite, ainsi que les conditionner :

```php
// messages flash
notify()
    ->info('message')
    ->success('message')
    ->when($condition, function($notify) {
        $notify->warning('message');
    })
    ->unless($otherCondition, function($notify) {
        $notify->error('message');
    });

// messages instantanés
notify()
    ->nowInfo('message')
    ->nowSuccess('message')
    ->when($condition, function($notify) {
        $notify->nowWarning('message');
    })
    ->unless($otherCondition, function($notify) {
        $notify->nowError('message');
    });
```

**Note :** Tous les templates ne prennent pas en charge la possibilité d'afficher plusieurs messages de type différents.

### Les stacks de messages

Il est possible de déclarer des messages dans une autre stack que celle par défaut.

Ainsi vous pourrez afficher différentes piles de messages dans une même page.

Par exemple utiliser la stack par défaut pour les messages courrants et occasionellement afficher des messages complémentaires selon le contexte de la page ; et ce avec par exemple un template différent à un emplacement différent.

Cela permet de mettre la logique métier dans le controller plutôt que dans les views Blade.

Pour se faire vous devez préciser le nom de la stack au helper `notify()` :

```php
notify()->success('message'); // stack par defaut
notify('custom-stack')->success('message'); // stack personnalisée

notify()->nowInfo('message'); // stack par defaut
notify('custom-stack')->nowInfo('message'); // stack personnalisée
```

Il est possible d'ajouter plusieurs messages à une même stack personnalisée :

```php
notify('custom-stack')
    ->nowInfo('message')
    ->nowSuccess('message')
    ->when($condition, function($notify) {
        $notify->nowWarning('message');
    });
```

> SVP : **n'utilisez jamais le nom "custom-stack"**, c'est ici pour l'exemple, choisissez un nom **explicite** selon le contexte.


### Les "view shared errors"

Il est possible d'ajouter via l'application des erreurs partagées par les vues de l'application.

Typiquemenent Laravel le fait automatiquement pour les messages d'erreurs de validation.

Mais il est également possible d'en ajouter par exemle via un controlleur :

```php
class function PostController()
{
    public function post(Request $request)
    {
        //...

        return back()->withErrors([
            'Une erreur',
            'Une autres erreur',
        ])
    }
}
```

Par défaut, ces messages d'erreurs partagés par toutes les vues sont automatiquement ajoutés **à la stack par défaut des messages instantanés.**

### Retrouver les messages

Il vous est possible de retrouver les messages "flash" et/ou "instantanés" sous forme de [Collecetion Laravel](https://laravel.com/docs/collections).

Retrouver les messages de la stack par défaut :

```php
notify()->flashMessages();

notify()->nowMessages();
```
Ou ceux d'une stack personnalisée :

```php
notify('custom-stack')->flashMessages();

notify('custom-stack')->nowMessages();
```

Cela peux vous permettre de manipuler le contenu des messages grâce aux collections Laravel.

Ou réaliser des opérations particulières selon la présence ou non de messages.

Par exemple si vous n'utilisez que des messages conditionnels et vous souhaitez déclencher une action si il y a effectivement des messages :

```php
notify()
    ->when($condition, function($notify) {
        $notify->warning('message');
    })
    ->unless($otherCondition, function($notify) {
        $notify->error('message');
    });

if (notify()->flashMessages()->isNotEmpty()) {
    return back();
}
```

Affichage des messages
----------------------

### Le component

Pour afficher les messages vous devez utiliser le component Blade fournit par le package :

```blade
<x-notify />
```

Selon le template de vue utilisé vous devez placer cet appel au bon endroit.

Par exemple pour un template qui utilise un composant Javascript vous devez le placer après les scripts de l'application.

A l'inverse si vous utilisez un template qui affiche directemet une vue vous devrez le placer là où vous souhaitez afficher les messages.

### Les attributs

Ce component prendra par défaut les valeurs de la configuration du package
mais vous pouvez les modifier en passant des attributs au component.

Il est également possible de piloter différents aspects de l'affichage des messages.

#### Template

Pour changer de template :

```blade
<x-notify view-name="notifier::bootstrap-5-alert" />
```

Cela peut-être utile par exemple pour afficher un template différent selon que vous êtes sur la partie publique ou la partie admin.
Ou encore un template différent selon les stacks affichées.

#### Affichage d'une stack donnée

Si vous avez déclaré des messages dans une stack différente de celle par défaut et souhaitez afficher ses messages :

```blade
<x-notify stack="custom-stack" />
```

#### Tri des messages selon leurs types

Par défaut les messages sont triés selon leurs types mais vous pouvez changer cela :

```blade
<x-notify :sort-by-type="false" />
```

Les messages apparaîtront alors dans l'ordre de leur déclaration quelque soit leur type.

**Note :**

Si vous n'utilisez pas cet attribut et laissez donc le tri selon le type, l'ordre d'affichage des types est définit dans le fichier de configuration (`sort_type_order`). Par défaut :

- error
- warning
- success
- info

#### Regroupement des messages par types

Par défaut chaque message déclenche une notification distincte. Il est possible de regrouper les messages selon leurs types :

```blade
<x-notify :group-by-type="true" />
```

Ainsi, si par exemple vous avez 3 erreurs de validation, elles apparaitrons toutes dans une seule et même notification plutôt que dans 3 notifications distinctes.

##### Mise en forme des messages regroupés

Les messages regroupés ont un format prédéfinit dans le fichier de configuration du package :

```php
//...
    'group_messages_format' => '<ul class="list-unstyled mb-0">%s</ul>',

    'group_title_format' => '<strong>%s&nbsp;:&nbsp;</strong>',

    'group_message_format' => '<li>%s%s</li>',
//...
```

*Notez l'utilisation des classes Bootstrap sur la liste...*

Ce qui donne par exemple :

```html
<ul class="list-unstyled mb-0">
    <li><strong>Success&nbsp;:&nbsp;</strong>féliciations</li>
    <li>encore féliciations</li>
</ul>
```

Il est possible de changer ces formats de façon globale en modifiant la configuration du package. Il n'est cependant pas possible de les modifier au niveau du component.

#### Messages à afficher

Parfois vous pouvez avoir besoin de n'afficher qu'une seule sorte de messages (flash ou instantanés).

Pour ne pas afficher les messages flash :

```blade
<x-notify :without-flash-messages="true" />
```

Pour ne pas afficher les messages intantanés :

```blade
<x-notify :without-now-messages="true" />
```

Par exemple pour afficher un template différent selon que ce sont des messages instantanés ou des messages flash.

```blade
<x-notify view-name="notifier::bootstrap-5-alert" :without-flash-messages="true" />

<x-notify :without-now-messages="true" />
```

#### View shared errors

Ces messages sont systématiquement affichés **dans la stack par défaut** et **dans les messages instantanés**.

Si vous ne souhaitez pas afficher ces erreurs via le notifier, vous pouvez ajouter au component de la stack par défaut :

```blade
<x-notify :without-view-shared-errors="true" />
```

A vous alors de gérer l'affichage de ces messages.

#### Combinaisons

Tous ces attributs peuvent êtres combinés selon les besoins :

```blade
<x-notify
    view-name="notifier::bootstrap-5-alert"
    stack="custom-stack"
    :sort-by-type="false"
    :group-by-type="true"
    :without-flash-messages="true"
    :without-now-messages="false"
    :without-view-shared-errors="true" />
```

Templates de vues
-----------------

 Le package fournis actuellement les templates de vues suivants :

- **bootstrap-5** code `notifier::bootstrap-5`
- **bootstrap-5-toast** code `notifier::bootstrap-5-toast`
- **bootstrap-5-alert** code `notifier::bootstrap-5-alert`
- **bootstrap-5-alert-advanced** code `notifier::bootstrap-5-alert-advanced`
- **bootstrap-4** code `notifier::bootstrap-4`
- **bootstrap-4-toast** code `notifier::bootstrap-4-toast`
- **bootstrap-4-alert** code `notifier::bootstrap-4-alert`
- **bootstrap-4-alert-advanced** code `notifier::bootstrap-4-alert-advanced`
- **sweetalert2** code `notifier::sweetalert2`
- **pnotify-5** code `notifier::pnotify-5`

### bootstrap-5

Affiche les messages dans de simples paragraphes aux [couleurs du type](https://getbootstrap.com/docs/5.1/utilities/colors/#colors).

Bootstrap 5 est évidement requis.

L'appel du component est à placer là où vous souhaitez que les messages apparaissent.

**Note :** Il n'est pas possible de grouper les messages par type avec cette vue.

### bootstrap-5-toast

Affiche les messages dans des components [toasts de Bootstrap 5](https://getbootstrap.com/docs/5.1/components/toasts/).

Bootstrap 5 est évidement requis ainsi que les styles et le plugin JS du component.

L'appel du component d'affichage est à placer après l'appel des scripts de l'application.

### bootstrap-5-alert

Affiche les messages dans des components [alerts de Bootstrap 5](https://getbootstrap.com/docs/5.1/components/alerts/).

Bootstrap 5 est évidement requis ainsi que les styles du component.

L'appel du component est à placer là où vous souhaitez que les alerts apparaissent.

### bootstrap-5-alert-advanced

C'est une extension du précédent qui ajoute une icone et le bouton pour fermer l'alert.

Il est donc nécessaire d'avoir dans votre projet le plugin JS des alerts Bootstrap.

L'appel du component est à placer là où vous souhaitez que les alerts apparaissent.

### bootstrap-4

Affiche les messages dans de simples paragraphes aux [couleurs du type](https://getbootstrap.com/docs/4.6/utilities/colors/#color).

Bootstrap 4 est évidement requis.

L'appel du component est à placer là où vous souhaitez que les messages apparaissent.

**Note :** Il n'est pas possible de grouper les messages par type avec cette vue.

### bootstrap-4-toast

Affiche les messages dans des components [toasts de Bootstrap 4](https://getbootstrap.com/docs/4.6/components/toasts/).

Bootstrap 4 est évidement requis ainsi que les styles et le plugin JS du component.

L'appel du component d'affichage est à placer après l'appel des scripts de l'application.

### bootstrap-4-alert

Affiche les messages dans des component [alerts de Bootstrap 4](https://getbootstrap.com/docs/4.6/components/alerts/).

Bootstrap 4 est évidement requis ainsi que les styles du component.

L'appel du component est à placer là où vous souhaitez que les alerts apparaissent.

### bootstrap-4-alert-advanced

C'est une extension du précédent qui ajoute une icone fontawesome5 et le bouton pour fermer l'alert.

Il est donc nécessaire d'avoir dans votre projet fontawesome4 et le JS de base de Bootstrap 4.

L'appel du component est à placer là où vous souhaitez que les alerts apparaissent.

### sweetalert2

> Aussi élégant visualement soit-il [sweetalert2](https://sweetalert2.github.io/)
> ne permet pas d'afficher plusieurs instances dans une même page,
> seule la première sera affichée (c'est en fait une sorte de modal).
>
> Vous ne pouvez donc pas déclarer plusieurs
> messages de différents types, aussi si vous utilisez cette vue les messages
> seront automatiquement groupés par type.

S'il n'est pas présent, installez-le :

```sh
npm install sweetalert2 --save-dev
```

Et ajoutez le à vos fichiers JS :

```js
import Swal from 'sweetalert2'
window.Swal = Swal;
```
Toute la configuration de l'affichage est dans le template.
Si vous souhaitez le modifier vous devez publier le template,
et vous référer à sa documentation.

L'appel du component d'affichage est à placer après l'appel des scripts de l'application.

### pnotify-5

> Même si ce package offre de nombreuses possibilités il est vieillissant et n'est plus vraiment maintenu ; aussi il requière jQuery.
>
> Pour ces raisons, bien que ce soit notre "standard" depuis de nombreuses années, je recommande de le remplacer par une autre solution plus moderne.
>
> Nous le maintenons quand même car de nombreux projets l'utilisent encore.

Ci-dessous un exemple d'installation, pour plus de détail veuillez vous référer à la documentation du package https://github.com/sciactive/pnotify

S'il n'est pas présent, installez-le :

```sh
npm install pnotify --save-dev
```

Ajoutez les CSS, par exemple :

```scss
// PNotify
@import '~@pnotify/core/dist/PNotify.css';
@import '~@pnotify/mobile/dist/PNotifyMobile.css';
@import '~@pnotify/bootstrap4/dist/PNotifyBootstrap4.css';
```

Chargez les modules, par exemple :

```js
// PNotify
window.PNotify = require('@pnotify/core');
window.PNotifyAnimate = require('@pnotify/animate');
window.PNotifyMobile = require('@pnotify/mobile');
window.PNotifyBootstrap4 = require('@pnotify/bootstrap4');
window.PNotifyFontAwesome5Fix = require('@pnotify/font-awesome5-fix');
window.PNotifyFontAwesome5 = require('@pnotify/font-awesome5');
```

Et une configuration par défaut, par exemple :

```js
PNotify.defaultStack.close(true);
PNotify.defaultStack.maxOpen = Infinity;
PNotify.defaultStack.modal = false;

PNotify.defaults.animateSpeed = 'slow';
PNotify.defaults.delay = 5000;
PNotify.defaults.titleTrusted = true;
PNotify.defaults.textTrusted = true;

PNotify.defaults.styling = 'bootstrap4';
PNotify.defaults.icons = 'fontawesome5';

PNotify.defaultModules.set(PNotifyMobile, {});
PNotify.defaultModules.set(PNotifyAnimate, {
    inClass: 'bounceInDown',
    outClass: 'bounceOut'
});
PNotify.defaultModules.set(PNotifyBootstrap4, {});
PNotify.defaultModules.set(PNotifyFontAwesome5, {});
```

L'appel du component d'affichage est à placer après l'appel des scripts de l'application.


Personnalisation des templates
------------------------------

Il vous est possible de personnaliser les templates de vue fournis par le package en les publiants avec cette commande :

```sh
php artisan vendor:publish --tag="notifier-views"
```

Elles seront alors copiées dans `resources/views/vendor/notifier` et automatiquement chargées par l'application.

**Astuce :**
*Ne remplacez que les fichiers de vues que vous personnalisez, les autres seront chargées depuis le package.*


Création d'un template personnalisé
-----------------------------------

Si vous souhaitez utiliser une librairie JS non implémentée de base dans le package ou simplement mettre en forme des messages avec du simple HTML, vous avez la possibilité de créer vos propres templates.

Pour commencer, créez votre propre vue Blade pour le component, par exemple :
`/resources/views/components/custom-notify.blade.php`

Pour l'utiliser précisez son utilisation lors de l'affichage, par exemple :

```blade
<x-notify view-name="components.custom-notify" />
```

Ou définissez-la comme vue par défaut dans le fichier de configuration.

> SVP : **n'utilisez jamais le nom "custom-notify"**, c'est ici pour l'exemple, choisissez un nom **explicite** selon votre implémentation personnalisée.

Évidement, vous aurez la possibilité d'utiliser tous les attributs du component `<x-notify />` comme vu au chapittre "Affichage des messages".

Dans votre nouvelle vue, vous aurez alors accès aux variables publiques du component :

- `$flashMessages` : la collection des messages flash
- `$nowMessages` : la collection des messages instantanés
- `$flashErrorsCount` : le nombre d'erreurs dans les messages flash
- `$nowErrorsCount` : le nombre d'erreurs dans les messages instantanés

Il conviendra alors de boucler sur les messages :

```blade
<div>
    @foreach ($flashMessages as $flashMessage)
        {!! ici votre implémentation d'affichage d'un message !!}
    @endforeach
    @foreach ($nowMessages as $nowMessage)
        {!! ici votre implémentation d'affichage d'un message !!}
    @endforeach
</div>
```

Dans ces boucles, les variables `$flashMessage` et `$nowMessage` sont des tableaux qui contiennent les valeurs suivantes :

```php
[
    'id', // l'identifiant unique du message
    'type', // le type du message ('info', 'success', 'warning' ou 'error')
    'message', // le contenu du message
    'title', // l'éventuel titre du message
    'delay', // la durée d'affichage du message
]
```

A ce stade la bonne idée c'est de créer une partial afin de mutualiser votre code, par exemple :
`/resources/views/components/partials/custom-notify-message.blade.php`

Et donc dans la vue du component :

```blade
<div>
    @foreach ($flashMessages as $flashMessage)
        @include ('components.partials.custom-notify-message', [
            'id' => $flashMessage['id'],
            'type' => $flashMessage['type'],
            'message' => $flashMessage['message'],
            'title' => $flashMessage['title'],
            'errorsCount' => $flashErrorsCount,
        ])
    @endforeach
    @foreach ($nowMessages as $nowMessage)
        @include ('components.partials.custom-notify-message', [
            'id' => $nowMessage['id'],
            'type' => $nowMessage['type'],
            'message' => $nowMessage['message'],
            'title' => $nowMessage['title'],
            'errorsCount' => $nowErrorsCount,
        ])
    @endforeach
</div>
```

Si vous n'avez pas besoin de modifier le code ci-dessus vous pouvez utiliser la vue component générique :

```blade
@include ('notifier::partials.a-generic-component', [
    'viewName' => 'components.partials.custom-notify-message',
])
```

Maintenant il ne vous reste "plus qu'à" implémenter la vue du message `components/partials/custom-notify-message.blade.php`.

Dans celle-ci vous aurez accès aux variables suivantes :

- `$id` : l'identifiant unique du message
- `$type` : le type du message ('info', 'success', 'warning' ou 'error')
- `$message` : le contenu du message
- `$title` : l'éventuel titre du message
- `$errorsCount` : le nombre de messages d'erreurs
- `$delay` : la durée d'affichage du message

Et là c'est à vous de jouer :)

> **Mutualisons :**
> Si vous implémentez un template réutilisable nous pourront envisager de l'intégrer au package plutôt que de le copier/coller entre différents projets.


Configuration
-------------

Ce package fournit un fichier de configuration.

*Consultez ce fichier pour voir ce que vous pouvez modifier, chacunes des options est documentée.*

Les valeurs de ce fichier sont accessibles de cette façon : `config('notifier.option')` où `option` est la clé du tableau de configuration.

Afin de personnaliser la configuration, vous devez publier le fichier dans votre application en exécutant la commande suivante :

```sh
php artisan vendor:publish --tag="notifier-config"
```

Le fichier sera alors copié dans `config/notifier.php` et automatiquement chargé par l'application.

**Astuce :** *ne mettez dans ce fichiers que ce que vous modifiez, le reste sera fusionné depuis le package.*

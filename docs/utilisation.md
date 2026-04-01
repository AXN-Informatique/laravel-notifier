---
title: Déclaration des messages
order: 2
---

Déclaration des messages
========================

Le point d'entrée est le helper `notify()` qui retourne une instance de `Axn\Notifier\Notify`.

Messages flash
--------------

Affichés à la requête HTTP **suivante** (typiquement après soumission d'un formulaire et redirection).

```php
notify()->info('message');
notify()->success('message');
notify()->warning('message');
notify()->error('message');
```

### Exemple

```php
public function update(Post $post)
{
    $post->update([/* ... */]);

    notify()->success('Post '.e($post->title).' mis à jour.');

    return back();
}
```

Messages instantanés
--------------------

Affichés dans la requête HTTP **courante** (sans redirection).

```php
notify()->nowInfo('message');
notify()->nowSuccess('message');
notify()->nowWarning('message');
notify()->nowError('message');
```

### Exemple

```php
public function edit(Post $post)
{
    notify()->nowInfo('Édition de '.e($post->title));

    return view('post.edit');
}
```

Titres
------

Ajouter un second argument `$title` :

```php
notify()->success('Post mis à jour.', 'Succès');
notify()->nowInfo('Édition en cours.', 'Information');
```

Durée d'affichage
-----------------

Troisième argument `$delay` (en millisecondes) :

```php
notify()->success('Message court.', 'Succès', 5000);
notify()->nowInfo('Message long à lire.', 'Information', 15000);
```

### Valeurs par défaut

| Type | Délai |
|------|-------|
| info | 8000 ms |
| success | 3000 ms |
| warning | 8000 ms |
| error | 8000 ms |

Le temps d'affichage des erreurs est multiplié par le nombre d'erreurs dans les templates fournis.

Sécurité XSS
-------------

Les variables `$message` et `$title` ne sont **pas échappées** dans les templates (pour permettre le HTML).

**Toujours échapper** les données utilisateur avec `e()` :

```php
// ✅ Correct
notify()->success('Post '.e($post->title).' mis à jour.');

// ❌ Faille XSS
notify()->success('Post '.$post->title.' mis à jour.');
```

Messages multiples et conditionnels
------------------------------------

```php
notify()
    ->info('message')
    ->success('message')
    ->when($condition, function ($notify) {
        $notify->warning('message');
    })
    ->unless($otherCondition, function ($notify) {
        $notify->error('message');
    });
```

Stacks
------

Organiser les messages en piles indépendantes :

```php
notify()->success('message');                   // stack par défaut
notify('custom-stack')->success('message');      // stack personnalisée

notify('custom-stack')
    ->nowInfo('message 1')
    ->nowSuccess('message 2');
```

Retrouver les messages
----------------------

Accéder aux messages sous forme de Collection Laravel :

```php
notify()->flashMessages();                      // stack par défaut
notify()->nowMessages();

notify('custom-stack')->flashMessages();        // stack personnalisée
notify('custom-stack')->nowMessages();
```

### Exemple : action conditionnelle

```php
notify()
    ->when($condition, fn ($n) => $n->warning('message'))
    ->unless($other, fn ($n) => $n->error('message'));

if (notify()->flashMessages()->isNotEmpty()) {
    return back();
}
```

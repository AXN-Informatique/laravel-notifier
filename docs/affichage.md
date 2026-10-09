---
title: Affichage des messages
order: 3
---

Affichage des messages
======================

Composant Blade
---------------

```blade
<x-notify />
```

Le placement dépend du template utilisé :
- **Templates JS** (SweetAlert2, PNotify, Bootstrap Toast) : après les scripts
- **Templates HTML** (Bootstrap, Bootstrap Alert) : à l'emplacement souhaité

Attributs
---------

### Template

```blade
<x-notify view-name="notifier::bootstrap-5-alert" />
```

### Stack

```blade
<x-notify stack="custom-stack" />
```

### Tri par type

Activé par défaut (option `sort_by_type`). Ordre configurable via `sort_type_order` :

```blade
<x-notify :sort-by-type="false" />
```

### Groupement par type

Regroupe les messages du même type en une seule notification (désactivé par défaut, option `group_by_type`) :

```blade
<x-notify :group-by-type="true" />
```

Le format des messages groupés est configurable dans `config/notifier.php`. Le message groupé n'a pas de titre (celui de chaque message passe devant son texte) et prend le délai du dernier message du type.

Certains templates imposent leur choix, quel que soit l'attribut : voir la section « Limitations par template » des [templates de vues](./templates.md).

### Filtrer les messages

```blade
{{-- Sans les messages flash --}}
<x-notify :without-flash-messages="true" />

{{-- Sans les messages instantanés --}}
<x-notify :without-now-messages="true" />
```

Exemple : templates différents selon le mode :

```blade
<x-notify view-name="notifier::bootstrap-5-alert" :without-flash-messages="true" />
<x-notify :without-now-messages="true" />
```

### View shared errors

Les erreurs partagées par les vues (validation Laravel, `withErrors()`) sont automatiquement ajoutées à la stack par défaut en tant que messages instantanés d'erreur :

- seul un composant de la stack par défaut les reprend ;
- seules les erreurs du sac par défaut sont reprises, pas celles d'un sac nommé (`withErrors($validator, 'login')`) ;
- elles ne sont reprises qu'une fois par requête, même si la page affiche plusieurs composants.

Pour les désactiver :

```blade
<x-notify :without-view-shared-errors="true" />
```

### Combinaison d'attributs

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

Récapitulatif des attributs
----------------------------

| Attribut | Type | Défaut | Description |
|----------|------|--------|-------------|
| `view-name` | string | config (`default_view`) | Template Blade à utiliser |
| `stack` | string | `default` | Nom de la stack |
| `sort-by-type` | bool | config (`sort_by_type`) | Trier par type |
| `group-by-type` | bool | config (`group_by_type`) | Grouper par type |
| `without-flash-messages` | bool | `false` | Masquer les messages flash |
| `without-now-messages` | bool | `false` | Masquer les messages instantanés |
| `without-view-shared-errors` | bool | `false` | Ignorer les erreurs partagées |

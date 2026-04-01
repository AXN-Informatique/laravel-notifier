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
- **Templates HTML** (Bootstrap Alert) : à l'emplacement souhaité

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

Activé par défaut. Ordre configurable via `sort_type_order` :

```blade
<x-notify :sort-by-type="false" />
```

### Groupement par type

Regroupe les messages du même type en une seule notification :

```blade
<x-notify :group-by-type="true" />
```

Le format des messages groupés est configurable dans `config/notifier.php`.

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

Les erreurs partagées par les vues (validation Laravel, `withErrors()`) sont automatiquement ajoutées à la stack par défaut en tant que messages instantanés.

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
| `view-name` | string | config | Template Blade à utiliser |
| `stack` | string | `default` | Nom de la stack |
| `sort-by-type` | bool | `true` | Trier par type |
| `group-by-type` | bool | `false` | Grouper par type |
| `without-flash-messages` | bool | `false` | Masquer les messages flash |
| `without-now-messages` | bool | `false` | Masquer les messages instantanés |
| `without-view-shared-errors` | bool | `false` | Ignorer les erreurs partagées |

---
title: Templates de vues
order: 4
---

Templates de vues
=================

Templates disponibles
---------------------

| Template | Code | Placement |
|----------|------|-----------|
| Bootstrap 5 | `notifier::bootstrap-5` | Dans le HTML |
| Bootstrap 5 Toast | `notifier::bootstrap-5-toast` | Après les scripts |
| Bootstrap 5 Alert | `notifier::bootstrap-5-alert` | Dans le HTML |
| Bootstrap 5 Alert Advanced | `notifier::bootstrap-5-alert-advanced` | Dans le HTML |
| Bootstrap 4 | `notifier::bootstrap-4` | Dans le HTML |
| Bootstrap 4 Toast | `notifier::bootstrap-4-toast` | Après les scripts |
| Bootstrap 4 Alert | `notifier::bootstrap-4-alert` | Dans le HTML |
| Bootstrap 4 Alert Advanced | `notifier::bootstrap-4-alert-advanced` | Dans le HTML |
| SweetAlert2 | `notifier::sweetalert2` | Après les scripts |
| PNotify 5 | `notifier::pnotify-5` | Après les scripts |
| PNotify 3 | `notifier::pnotify-3` | Après les scripts |

Bootstrap 5
-----------

Affiche les messages dans des paragraphes aux couleurs du type. Ne supporte pas le groupement par type.

**Prérequis** : Bootstrap 5

Bootstrap 5 Toast
-----------------

Affiche les messages dans des [toasts Bootstrap 5](https://getbootstrap.com/docs/5.1/components/toasts/).

**Prérequis** : Bootstrap 5 + JS du composant toast

Bootstrap 5 Alert / Alert Advanced
-----------------------------------

Affiche les messages dans des [alerts Bootstrap 5](https://getbootstrap.com/docs/5.1/components/alerts/). La version « Advanced » ajoute une icone et un bouton de fermeture.

**Prérequis** : Bootstrap 5 (+ JS du composant alert pour la version Advanced)

SweetAlert2
-----------

Utilise [SweetAlert2](https://sweetalert2.github.io/) pour afficher les messages dans une modale. Ne peut afficher qu'une instance à la fois : les messages sont automatiquement groupés par type.

**Prérequis** :

```bash
npm install sweetalert2 --save-dev
```

```js
import Swal from 'sweetalert2'
window.Swal = Swal;
```

PNotify 5
---------

Utilise [PNotify 5](https://github.com/sciactive/pnotify). Requiert jQuery. Ce plugin n'est plus maintenu activement.

**Prérequis** : PNotify 5, jQuery, Bootstrap 4, Font Awesome 5

PNotify 3
---------

Support de PNotify 3 pour les projets legacy.

**Prérequis** : PNotify 3

Limitations par template
------------------------

| Template | Groupement par type | Messages multiples |
|----------|--------------------|--------------------|
| `bootstrap-5` | ❌ Forcé à `false` | ✅ |
| `bootstrap-4` | ❌ Forcé à `false` | ✅ |
| `sweetalert2` | ✅ Forcé à `true` | ❌ (1 seule modale) |
| Autres | ✅ Optionnel | ✅ |

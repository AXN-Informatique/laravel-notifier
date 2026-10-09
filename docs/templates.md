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

Affiche les messages dans des [toasts Bootstrap 5](https://getbootstrap.com/docs/5.1/components/toasts/), en haut à droite de la page.

**Prérequis** : Bootstrap 5 et son JS exposé en `window.bootstrap` (le template appelle `new bootstrap.Toast()`)

Bootstrap 5 Alert / Alert Advanced
-----------------------------------

Affiche les messages dans des [alerts Bootstrap 5](https://getbootstrap.com/docs/5.1/components/alerts/). La version « Advanced » ajoute une icône (SVG intégré au template) et un bouton de fermeture.

**Prérequis** : Bootstrap 5 (+ JS du composant alert pour la version Advanced)

Bootstrap 4
-----------

Affiche les messages dans des paragraphes aux couleurs du type. Ne supporte pas le groupement par type.

**Prérequis** : Bootstrap 4

Bootstrap 4 Toast
-----------------

Affiche les messages dans des [toasts Bootstrap 4](https://getbootstrap.com/docs/4.6/components/toasts/), en haut à droite de la page.

**Prérequis** : Bootstrap 4, son JS et jQuery (le template appelle `$('#…').toast()`)

Bootstrap 4 Alert / Alert Advanced
-----------------------------------

Affiche les messages dans des [alerts Bootstrap 4](https://getbootstrap.com/docs/4.6/components/alerts/). La version « Advanced » ajoute une icône Font Awesome et un bouton de fermeture.

**Prérequis** : Bootstrap 4 (+ pour la version Advanced : JS du composant alert, et Font Awesome Pro pour les icônes du style Light, classes `fal`)

SweetAlert2
-----------

Utilise [SweetAlert2](https://sweetalert2.github.io/) pour afficher les messages dans un toast en haut de page, avec une barre de progression et un bouton de fermeture ; le survol suspend le minuteur.

SweetAlert2 n'affiche qu'une fenêtre à la fois : le template force donc le groupement par type, et des messages de types différents dans une même requête ne s'affichent pas tous.

**Prérequis** :

```bash
npm install sweetalert2 --save-dev
```

```js
import Swal from 'sweetalert2'
window.Swal = Swal;
```

Le template emploie les classes d'animation d'[Animate.css](https://animate.style/) (`animate__bounceInDown`, `animate__bounceOut`) et les classes de couleur de Bootstrap (`bg-*`, `text-*`) : sans ces feuilles de style, le toast s'affiche sans animation ni couleurs Bootstrap.

PNotify 5
---------

Utilise [PNotify 5](https://github.com/sciactive/pnotify) (`PNotify.alert()`). Le type `warning` est transmis en `notice`, son équivalent PNotify. Ce plugin n'est plus maintenu activement.

**Prérequis** : PNotify 5 exposé en `window.PNotify`. Le style et les icônes (Bootstrap 4 et Font Awesome 5 dans nos projets) se règlent dans la configuration de PNotify. Les messages pouvant contenir du HTML, dont les messages groupés, autoriser le HTML dans le texte et le titre :

```js
import * as PNotify from '@pnotify/core';
window.PNotify = PNotify;

PNotify.defaults.textTrusted = true;
PNotify.defaults.titleTrusted = true;
```

PNotify 3
---------

Support de PNotify 3 pour les projets legacy.

**Prérequis** : PNotify 3 et jQuery (le template crée les notifications au chargement de la page, via `jQuery(function () { … })`)

Limitations par template
------------------------

| Template | Groupement par type | Messages multiples |
|----------|--------------------|--------------------|
| `bootstrap-5` | ❌ Forcé à `false` | ✅ |
| `bootstrap-4` | ❌ Forcé à `false` | ✅ |
| `sweetalert2` | ✅ Forcé à `true` | ❌ (une seule fenêtre à la fois) |
| Autres | ✅ Optionnel | ✅ |

Le forçage dépend du nom de la vue (`notifier::bootstrap-5`, `notifier::bootstrap-4`, `notifier::sweetalert2`) et s'applique donc aussi à une vue publiée. Un template personnalisé qui reprend leur code sous un autre nom ne le reçoit pas.

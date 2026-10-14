# Plugiit Announcements

Annonces planifiées pour Azuriom : barres en haut du site et popups modales.

## Fonctionnalités

- **Barres** en haut de toutes les pages et/ou **popup** modale.
- Message en **Markdown** ou en **HTML simple sécurisé** (liste blanche : `strong`, `em`, `a`, `br`, `p`, `ul`, `li`, `code`… ; scripts, attributs d'événement et liens `javascript:` supprimés).
- Styles : information, succès, alerte, danger ou couleurs personnalisées (fond + texte).
- Bouton d'action optionnel (texte + lien absolu ou relatif).
- Dates de début et de fin, avec **compte à rebours** optionnel jusqu'à la fin (la barre disparaît d'elle-même à l'échéance).
- Ciblage du public : tout le monde, visiteurs non connectés, membres connectés ou rôles précis.
- Ciblage des pages : toutes, accueil, boutique ou motifs d'URL (`forum/*`, `vote`, `/` pour l'accueil…).
- Barres fermables par le visiteur, mémorisé dans son navigateur (`localStorage`) par identifiant **et version** : modifier le titre, le message ou le bouton réaffiche l'annonce.
- Fréquence du popup : une seule fois, une fois par session ou à chaque chargement.
- Priorité : ordre des barres et choix du popup (**un seul popup par chargement**, le plus prioritaire qui n'a pas déjà été vu).
- Plusieurs barres actives : **empilées** ou **en rotation** (durée réglable, pause au survol et au focus, pastilles de navigation).
- Le popup attend que le visiteur ait répondu au bandeau de [Plugiit Cookie Consent](../plugiit-cookie-consent) s'il est installé, pour ne pas empiler deux fenêtres.
- Cache des annonces actives, invalidé à chaque création, modification ou suppression.
- Accessible : région ARIA, fenêtre modale avec focus piégé, Échap pour fermer, boutons libellés.

## Installation

1. Copier le dossier dans `plugins/plugiit-announcements` puis activer le plugin dans **Admin > Plugins**.
2. Créer les annonces dans **Admin > Annonces**.

## Administration

Permission : `plugiit-announcements.admin`.

| Page | URL |
|---|---|
| Liste des annonces | `/admin/plugiit-announcements/announcements` |
| Créer une annonce | `/admin/plugiit-announcements/announcements/create` |
| Réglages (barres empilées / rotation) | `/admin/plugiit-announcements/settings` |

Le plugin n'a pas de page publique : les annonces sont injectées par un middleware global sur toutes les pages HTML (hors administration, API, AJAX et pages d'erreur). Les barres sont insérées juste après `<body>`, les popups et le script avant `</body>`.

## Intégration au thème

Pour placer les barres ailleurs (par exemple sous la barre de navigation), ajoutez dans le thème :

```blade
@include('plugiit-announcements::bars')
```

L'insertion automatique des barres est alors désactivée pour cette page (les popups restent injectés automatiquement).

Événement émis sur `document` à la fermeture d'un popup : `plugiit-ann:popup-closed`. Fonction globale : `window.PlugiitAnnouncements.closePopup()`.

## Cohabitation

Tout le HTML, le CSS et les identifiants sont préfixés par `plugiit-ann-` ; le script est une IIFE sans dépendance. Z-index : barres 1031 (dans le flux de la page), popup 1060, sous le bandeau de cookies (1080).

## Limites

- Les dates sont interprétées dans le fuseau horaire du site.
- La mémorisation « fermée » / « déjà vue » est propre au navigateur du visiteur (pas de suivi côté serveur).

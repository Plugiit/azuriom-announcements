<?php

return [
    'nav' => [
        'title' => 'Annonces',
        'announcements' => 'Annonces',
        'settings' => 'Réglages',
    ],

    'permission' => 'Gérer les annonces (barres et popups)',

    'announcements' => [
        'title' => 'Annonces',
        'create' => 'Créer une annonce',
        'edit' => 'Modifier l\'annonce :title',
        'empty' => 'Aucune annonce pour le moment.',
        'info' => 'Plusieurs barres peuvent être actives en même temps ; un seul popup est affiché par chargement de page (celui de plus haute priorité qui n\'a pas déjà été vu).',

        'content' => 'Contenu',
        'display' => 'Affichage',
        'schedule' => 'Planification',
        'targeting' => 'Ciblage',

        'fields' => [
            'title' => 'Titre',
            'message' => 'Message',
            'format' => 'Format du message',
            'type' => 'Type',
            'style' => 'Style',
            'color' => 'Couleur de fond',
            'text_color' => 'Couleur du texte',
            'button_label' => 'Texte du bouton',
            'button_url' => 'Lien du bouton',
            'starts_at' => 'Début',
            'ends_at' => 'Fin',
            'has_countdown' => 'Afficher un compte à rebours jusqu\'à la date de fin',
            'audience' => 'Public',
            'roles' => 'Rôles',
            'pages' => 'Pages',
            'page_patterns' => 'Motifs d\'URL',
            'is_dismissible' => 'Fermable par le visiteur (mémorisé dans son navigateur)',
            'frequency' => 'Fréquence d\'affichage',
            'priority' => 'Priorité',
            'is_enabled' => 'Activée',
            'period' => 'Période',
            'status' => 'Statut',
        ],

        'help' => [
            'message' => 'Markdown : **gras**, *italique*, [lien](https://…). HTML simple : <strong>, <em>, <a>, <br>, <ul>… (les autres balises et attributs sont supprimés).',
            'button' => 'Optionnel. Lien absolu (https://…) ou relatif (/shop).',
            'dates' => 'Laisser vide pour une annonce sans début ou sans fin. Fuseau horaire du site.',
            'page_patterns' => 'Un motif par ligne, * pour n\'importe quelle suite. Exemples : vote, forum/*, shop/categories/*. Utilisez / pour l\'accueil.',
            'priority' => 'Les annonces de plus haute priorité sont affichées en premier.',
            'dismissible' => 'Une modification du titre, du message ou du bouton réaffiche l\'annonce aux visiteurs qui l\'avaient fermée.',
            'frequency' => 'Le popup peut toujours être fermé ; la fréquence détermine quand il est réaffiché.',
        ],

        'formats' => [
            'markdown' => 'Markdown',
            'html' => 'HTML simple',
        ],
        'types' => [
            'bar' => 'Barre en haut du site',
            'popup' => 'Popup (fenêtre modale)',
        ],
        'styles' => [
            'info' => 'Information (bleu)',
            'success' => 'Succès (vert)',
            'warning' => 'Alerte (orange)',
            'danger' => 'Danger (rouge)',
            'custom' => 'Couleurs personnalisées',
        ],
        'audiences' => [
            'all' => 'Tout le monde',
            'guests' => 'Visiteurs non connectés',
            'users' => 'Membres connectés',
            'roles' => 'Rôles précis',
        ],
        'pages_options' => [
            'all' => 'Toutes les pages',
            'home' => 'Page d\'accueil',
            'shop' => 'Boutique',
            'pattern' => 'Motif d\'URL',
        ],
        'frequencies' => [
            'once' => 'Une seule fois',
            'session' => 'Une fois par session de navigation',
            'always' => 'À chaque chargement de page',
        ],
        'statuses' => [
            'active' => 'En cours',
            'scheduled' => 'Programmée',
            'expired' => 'Terminée',
            'disabled' => 'Désactivée',
        ],
        'no_start' => 'Dès maintenant',
        'no_end' => 'sans fin',
    ],

    'settings' => [
        'title' => 'Réglages des annonces',
        'bar_mode' => 'Affichage de plusieurs barres actives',
        'bar_modes' => [
            'stacked' => 'Empilées les unes sous les autres',
            'rotate' => 'En rotation (une barre à la fois)',
        ],
        'rotation_delay' => 'Durée d\'affichage de chaque barre en rotation',
        'seconds' => 'secondes',
        'integration' => 'Intégration au thème',
        'integration_info' => 'Les barres sont insérées automatiquement juste après la balise <body>. Pour les placer ailleurs (par exemple sous la barre de navigation), ajoutez :include dans le thème : l\'insertion automatique est alors désactivée.',
    ],

    'logs' => [
        'plugiit-announcements' => [
            'created' => 'Création de l\'annonce #:id',
            'updated' => 'Mise à jour de l\'annonce #:id',
            'deleted' => 'Suppression de l\'annonce #:id',
        ],

        'settings' => 'Mise à jour des réglages des annonces',
    ],
];

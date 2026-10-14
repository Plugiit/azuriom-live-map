<?php

return [
    'nav' => [
        'title' => 'Carte en direct',
        'maps' => 'Cartes',
        'settings' => 'Paramètres',
    ],

    'permission' => 'Gérer les cartes en direct',

    'maps' => [
        'title' => 'Cartes',
        'create' => 'Ajouter une carte',
        'edit' => 'Modifier la carte :name',
        'empty' => 'Aucune carte configurée.',
        'default' => 'Par défaut',
        'type' => 'Type de carte',
        'url' => 'URL publique de la carte',
        'url_info' => 'Adresse web de la carte telle qu\'elle s\'ouvre dans un navigateur (http ou https), par exemple https://map.monserveur.fr/.',
        'server' => 'Serveur associé',
        'no_server' => 'Aucun',
        'icon' => 'Icône',
        'icon_info' => 'Classe Bootstrap Icons, par exemple « bi bi-map ». Laissez vide pour l\'icône par défaut du type.',
        'position' => 'Position',
        'enable' => 'Afficher cette carte',
        'test' => 'Tester l\'URL',
        'testing' => 'Test en cours…',
        'view' => 'Voir',
    ],

    'test' => [
        'invalid_url' => 'L\'URL doit commencer par http:// ou https://.',
        'unreachable' => 'Impossible de joindre la carte depuis le serveur web : :error',
        'http_error' => 'La carte répond avec le code HTTP :status.',
        'reachable' => 'La carte répond (HTTP :status).',
        'embeddable' => 'Aucun en-tête n\'interdit l\'intégration : la carte peut s\'afficher sur votre site.',
        'xfo_blocked' => 'L\'en-tête « X-Frame-Options: :value » interdit l\'affichage de la carte dans une iframe sur votre site.',
        'csp_blocked' => 'La directive CSP « frame-ancestors :value » interdit l\'affichage de la carte sur votre site.',
        'how_to_fix' => 'À corriger côté serveur de carte (ou du proxy inverse devant elle) : supprimez l\'en-tête X-Frame-Options et autorisez votre site avec « Content-Security-Policy: frame-ancestors \'self\' :site ». Avec Nginx : « proxy_hide_header X-Frame-Options; add_header Content-Security-Policy "frame-ancestors \'self\' :site"; ». BlueMap, Dynmap, squaremap et Pl3xMap n\'envoient pas ces en-têtes par défaut avec leur serveur web intégré : ils viennent en général d\'un proxy ou d\'un hébergeur.',
        'mixed_content' => 'Votre site est en HTTPS mais la carte en HTTP : les navigateurs bloqueront l\'iframe (contenu mixte). Servez la carte en HTTPS, par exemple derrière un proxy inverse avec un certificat.',
        'error' => 'Le test a échoué.',
    ],

    'settings' => [
        'title' => 'Paramètres de la carte',
        'height' => 'Hauteur de la carte',
        'height_info' => 'Sur mobile, la hauteur est limitée à 75 % de l\'écran.',
        'default_map' => 'Carte affichée par défaut',
        'first_map' => 'La première carte active',
        'fullscreen' => 'Afficher le bouton plein écran',
        'new_tab' => 'Afficher le lien « Ouvrir dans un nouvel onglet »',
        'inactive' => 'désactivée',
        'widget' => 'Widget pour votre thème',
        'widget_info' => 'Insérez la carte par défaut dans n\'importe quelle vue Blade :',
    ],

    'logs' => [
        'plugiit-live-map-maps' => [
            'created' => 'Création de la carte #:id',
            'updated' => 'Mise à jour de la carte #:id',
            'deleted' => 'Suppression de la carte #:id',
        ],
        'settings' => 'Mise à jour des paramètres de la carte en direct',
    ],
];

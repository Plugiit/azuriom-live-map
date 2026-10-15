<?php

return [
    'nav' => [
        'title' => 'Live map',
        'maps' => 'Maps',
        'settings' => 'Settings',
    ],

    'permission' => 'Manage live maps',

    'maps' => [
        'title' => 'Maps',
        'create' => 'Add a map',
        'edit' => 'Edit map :name',
        'empty' => 'No map configured.',
        'default' => 'Default',
        'type' => 'Map type',
        'url' => 'Public map URL',
        'url_info' => 'Web address of the map as opened in a browser (http or https), for example https://map.myserver.com/.',
        'server' => 'Linked server',
        'no_server' => 'None',
        'icon' => 'Icon',
        'icon_info' => 'Bootstrap Icons class, for example "bi bi-map". Leave empty to use the default icon of the type.',
        'position' => 'Position',
        'enable' => 'Display this map',
        'test' => 'Test the URL',
        'testing' => 'Testing…',
        'view' => 'View',
    ],

    'test' => [
        'invalid_url' => 'The URL must start with http:// or https://.',
        'unreachable' => 'Unable to reach the map from the web server: :error',
        'http_error' => 'The map answers with HTTP status :status.',
        'reachable' => 'The map answers (HTTP :status).',
        'embeddable' => 'No header forbids embedding: the map can be displayed on your website.',
        'xfo_blocked' => 'The "X-Frame-Options: :value" header forbids displaying the map in an iframe on your website.',
        'csp_blocked' => 'The CSP directive "frame-ancestors :value" forbids displaying the map on your website.',
        'how_to_fix' => 'To fix on the map server (or the reverse proxy in front of it): remove the X-Frame-Options header and allow your website with "Content-Security-Policy: frame-ancestors \'self\' :site". With Nginx: "proxy_hide_header X-Frame-Options; add_header Content-Security-Policy "frame-ancestors \'self\' :site";". BlueMap, Dynmap, squaremap and Pl3xMap do not send these headers by default with their built-in web server: they usually come from a proxy or a host.',
        'mixed_content' => 'Your website uses HTTPS but the map uses HTTP: browsers will block the iframe (mixed content). Serve the map over HTTPS, for example behind a reverse proxy with a certificate.',
        'error' => 'The test failed.',
    ],

    'settings' => [
        'title' => 'Map settings',
        'height' => 'Map height',
        'height_info' => 'On mobile, the height is limited to 75% of the screen.',
        'default_map' => 'Map displayed by default',
        'first_map' => 'The first enabled map',
        'fullscreen' => 'Display the fullscreen button',
        'new_tab' => 'Display the "Open in a new tab" link',
        'inactive' => 'disabled',
        'widget' => 'Widget for your theme',
        'widget_info' => 'Insert the default map in any Blade view:',
    ],

    'logs' => [
        'live-map-maps' => [
            'created' => 'Created map #:id',
            'updated' => 'Updated map #:id',
            'deleted' => 'Deleted map #:id',
        ],
        'settings' => 'Updated live map settings',
    ],
];

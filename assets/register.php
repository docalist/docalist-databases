<?php
/**
 * This file is part of Docalist Data.
 *
 * Copyright (C) 2012-2019 Daniel Ménard
 *
 * For copyright and license information, please view the
 * LICENSE file that was distributed with this source code.

 * @author Daniel Ménard <daniel.menard@laposte.net>
 */
declare(strict_types=1);

namespace Docalist\Data;

// Les scripts suivants ne sont dispos que dans le back-office
add_action('admin_init', function () {
    $base = DOCALIST_DATA_URL;

    // Editeur de grille
    wp_register_script(
        'docalist-data-grid-edit',
        "$base/views/grid/edit.js",
        ['jquery', 'jquery-ui-sortable'],
        '20190925',
        true
    );

    wp_register_style(
        'docalist-data-grid-edit',
        "$base/views/grid/edit.css",
        [],
        '20190925'
    );

    // google-maps et google-maps-place

    // Si on n'a pas de clé d'api, on va ajouter une dépendance qui génèrent un warning (pour les admins)
    $warnings = [];

    // Clé d'API à utiliser
    $key = defined('GOOGLE_API_KEY') ? GOOGLE_API_KEY : '';
    empty($key) && current_user_can('manage_options') && $warnings[] = 'no-google-api-key';

    // Langue de l'API
    $language = defined('GOOGLE_MAPS_LANGUAGE') ? GOOGLE_MAPS_LANGUAGE : 'fr';

    // Pays/région à utiliser
    $region = defined('GOOGLE_MAPS_REGION') ? GOOGLE_MAPS_REGION : 'FR';

    // Déclare les dépendances des warnings
    foreach ($warnings as $warning) {
        wp_register_script($warning, "$base/assets/$warning.js", [], '20190205', true);
    }

    // Déclare le handle 'google-maps'
    wp_register_script(
        'google-maps',
        sprintf(
            'https://maps.googleapis.com/maps/api/js?key=%s&language=%s&region=%s',
            rawurlencode($key),
            rawurlencode($language),
            rawurlencode($region)
        ),
        $warnings,
        null, // Ne pas générer de numéro de version, Google gère lui-même son versionning
        true
    );

    // Déclare le handle 'google-maps-places'
    wp_register_script(
        'google-maps-places',
        sprintf(
            'https://maps.googleapis.com/maps/api/js?libraries=places&key=%s&language=%s&region=%s',
            rawurlencode($key),
            rawurlencode($language),
            rawurlencode($region)
        ),
        $warnings,
        null, // Ne pas générer de numéro de version, Google gère lui-même son versionning
        true
    );

    // Adresse Postale
    wp_register_style(
        'docalist-postal-address',
        "$base/assets/postal-address/postal-address.css",
        [],
        '190604'
    );

    wp_register_script(
        'docalist-postal-address',
        "$base/assets/postal-address/postal-address.js",
        ['jquery', 'google-maps-places'],
        '20151229',
        true
    );
});

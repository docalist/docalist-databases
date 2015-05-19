<?php
/**
 * This file is part of the "Docalist Biblio Export" plugin.
 *
 * Copyright (C) 2015-2015 Daniel Ménard
 *
 * For copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 *
 * @package     Docalist\Biblio\Export
 * @author      Daniel Ménard <daniel.menard@laposte.net>
 * @version     SVN: $Id$
 */
namespace Docalist\Biblio\Export;

use Docalist\Type\Settings as TypeSettings;
use Docalist\Type\Integer;

/**
 * Options de configuration du plugin.
 *
 * @property Integer $exportpage ID de la page "export".
 */
class Settings extends TypeSettings {
    protected $id = 'docalist-biblio-export';

    static protected function loadSchema() {
        return [
            'fields' => [
                'exportpage' => [
                    'type' => 'int',
                    'label' =>__("Page pour l'export", 'docalist-biblio-export'),
                    'description' => __("Page WordPress sur laquelle l'export sera disponible.", 'docalist-biblio-export'),
                    'default' => 0,
                ],
            ]
        ];
    }
}
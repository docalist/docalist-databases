<?php
/**
 * This file is part of the 'Docalist Biblio' plugin.
 *
 * Copyright (C) 2012-2017 Daniel Ménard
 *
 * For copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 *
 * @package     Docalist
 * @subpackage  Biblio
 * @author      Daniel Ménard <daniel.menard@laposte.net>
 */
namespace Docalist\Biblio\Field;

use Docalist\Type\TableEntry;

/**
 * Langue du document.
 *
 * Ce champ permet de préciser la langue du document catalogué : la langue des textes pour un document écrit, les
 * pistes audio disponibles pour un film, etc.
 *
 * Le champ est associé à une table d'autorité qui indique les langues possibles (par défaut : codes ISO à deux
 * lettres des langues de l'Union Européenne).
 */
class Language extends TableEntry
{
    public static function loadSchema()
    {
        return [
            'label' => __('Langue', 'docalist-biblio'),
            'description' => __(
                'Langue du document : langue des textes qui figurent dans le document, pistes audio disponibles...',
                'docalist-biblio'
            ),
            'table' => 'table:ISO-639-2_alpha3_EU_fr',
        ];
    }
}

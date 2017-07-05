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

use Docalist\Type\Text;
use Docalist\Forms\EntryPicker;

/**
 * Un titre de périodique.
 */
class Journal extends Text
{
    public static function loadSchema()
    {
        return [
            'label' => __('Périodique', 'docalist-biblio'),
            'description' => __(
                'Nom du journal (revue, magazine, périodique...) dans lequel a été publié le document.',
                'docalist-biblio'
            ),
        ];
    }

    public function getEditorForm($options = null)
    {
        return (new EntryPicker('journal'))->setOptions('index:journal.filter')->addClass('large-text');
    }
}

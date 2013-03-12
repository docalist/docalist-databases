<?php
/**
 * This file is part of a "Docalist Biblio" plugin.
 *
 * For copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 *
 * @package     Docalist
 * @subpackage  Biblio
 * @author      Daniel Ménard <daniel.menard@laposte.net>
 * @version     SVN: $Id$
 */

namespace Docalist\Biblio\Metabox;

use Docalist\Metabox, Docalist\Forms\Fieldset;

class Authors extends Metabox {
    /**
     * @inheritdoc
     */
    public function __construct() {
        $box = new Fieldset();

        //@formatter:off
        $box->label(__('Auteurs', 'docalist-biblio'));

        $box->table('author')
            ->label('Personnes')
            ->repeatable(true)
                ->input('name')
                ->label('Nom')
                ->addClass('span5')
            ->parent()
                ->input('firstname')
                ->label('Prénom')
                ->addClass('span4')
            ->parent()
                ->select('role')
                ->label('Rôle')
                ->options(array('pref','trad','ill','dir','coord','postf','intro'))
                ->addClass('span3');

        $box->table('organisation')
            ->label('Organismes')
            ->repeatable(true)
                ->input('name')
                ->label('Nom')
                ->addClass('span5')
            ->parent()
                ->input('city')
                ->label('Ville')
                ->addClass('span3')
            ->parent()
                ->select('country')
                ->label('Pays')
                ->options(array('france', 'usa', 'espagne', 'italie'))
                ->addClass('span2')
            ->parent()
                ->select('role')
                ->label('Rôle')
                ->options(array('com','financ'))
                ->addClass('span2');

        //@formatter:on

        $this->form = $box;
    }
}
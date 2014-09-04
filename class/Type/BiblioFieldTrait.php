<?php
/**
 * This file is part of the 'Docalist Biblio' plugin.
 *
 * Copyright (C) 2012-2014 Daniel Ménard
 *
 * For copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 *
 * @package     Docalist
 * @subpackage  Biblio
 * @author      Daniel Ménard <daniel.menard@laposte.net>
 * @version     $Id$
 */
namespace Docalist\Biblio\Type;

use Docalist\Forms\Fragment;
use Docalist\Forms\Tag;
use Docalist\Table\TableManager;
use Docalist\Table\TableInterface;
use Docalist\Schema\Field;

trait BiblioFieldTrait {
    /**
     * Implémentation de base de BiblioField::settingsForm().
     *
     * Retourne un formulaire qui contient les contrôles name, label et
     * description.
     *
     * @return Fragment
     */
    public function settingsForm() {
        $name = $this->schema->name();
        $form = new Fragment($name);
        $form->hidden('name')
             ->attribute('class', 'name');
        $form->input('label')
             ->attribute('id', $name . '-label')
             ->attribute('class', 'label regular-text')
             ->label(__('Libellé', 'docalist-biblio'));
        $form->textarea('description')
             ->attribute('id', $name . '-description')
             ->attribute('class', 'description large-text')
             ->attribute('rows', 2)
             ->label(__('Description', 'docalist-biblio'));

        return $form;
    }

    /**
     * Retourne toutes les tables d'un type donné.
     * Cette méthode utilitaire sert aux champs qui utilisent ce trait pour
     * afficher un select contenant la liste des tables possibles (par exemple
     * la liste des tables de type "pays" pour le champ Organisation).
     *
     * @param string $type Le type souhaité/
     *
     * @return array Un tableau de la forme code => libellé utilisable dans un
     * select.
     */
    protected function tablesOfType($type) {
        /* @var $tableManager TableManager */
        $tableManager = docalist('table-manager');

        /* @var $tableInfo TableInfo */
        $tables = [];
        foreach($tableManager->info(null, $type) as $name => $tableInfo) {
            if ($tableInfo->format() !== 'conversion') {
                $key = $tableInfo->format() . ':' . $name;
                $tables[$key] = sprintf('%s (%s)', $tableInfo->label(), $name);
            }
        }

        return $tables;
    }

    /**
     * Génère une erreur si un champ n'a pas implémenté la méthode
     * BiblioField::editForm.
     *
     * @return Tag
     */
    public function editForm() {
        return new Tag('p', 'la classe ' . get_class($this) . ' doit implémenter editForm().');
    }

    /**
     * Implémentation par défaut de BiblioFIeld::map().
     *
     * Par défaut, ne fait rien.
     *
     * @param array $doc
     */
    public function map(array & $doc) {

    }

    /**
     * Ouvre la table indiquée dans le schéma.
     *
     * @param bool $table2 Par défaut, c'est la table indiquée dans la propriété
     * 'table' du schéma du champ qui est ouverte. Si vous passez true, c'est la
     * table indiquée dans la propriété 'table2' qui sera utilisée (exemple :
     * author, editor).
     *
     * @return TableInterface
     */
    protected function openTable($table2 = false) {
        // Détermine la table à utiliser
        $table = $table2 ? $this->schema()->table2() : $this->schema()->table();

        // Le nom de la table est de la forme "type:nom", on ne veut que le nom
        $table = explode(':', $table)[1];

        return docalist('table-manager')->get($table);
    }

    /**
     * Implémentation par défaut de BiblioFIeld::ESmapping().
     *
     * Par défaut, ne fait rien.
     *
     * @param array $doc
     */
    public static function ESmapping(array & $mappings, Field $schema) {
    }

    /**
     * Mapping standard pour un champ texte.
     *
     * @param bool $includeInAll Indique s'il faut ajouter la clause
     * "include_in_all" au mapping (false par défaut).
     *
     * @param string $analyzer Nom de l'analyseur à utiliser.
     *
     * @return array
     */
    protected static function stdIndex($includeInAll = false, $analyzer = 'dclref-default-fr') {
        $mapping =  [
            'type' => 'string',
            'analyzer' => $analyzer,
        ];

        if ($includeInAll) {
            $mapping['include_in_all'] = true;
        }

        return $mapping;
    }

    /**
     * Mapping standard champ texte + filtre.
     *
     * @param bool $includeInAll Indique s'il faut ajouter la clause
     * "include_in_all" au mapping (false par défaut).
     *
     * @param string $analyzer Nom de l'analyseur à utiliser.
     *
     * @return array
     */
    protected static function stdIndexAndFilter($includeInAll = false, $analyzer = 'dclref-default-fr') {
        $mapping = self::stdIndex($includeInAll, $analyzer);

        $mapping['fields'] = [
            'filter' => [
                'type' => 'string',
                'index' => 'not_analyzed',
            ]
        ];

        return $mapping;
    }

    /**
     * Mapping standard champ texte + filtre + lookup.
     *
     * @param bool $includeInAll Indique s'il faut ajouter la clause
     * "include_in_all" au mapping (false par défaut).
     *
     * @param string $analyzer Nom de l'analyseur à utiliser.
     *
     * @return array
     */
    protected static function stdIndexFilterAndSuggest($includeInAll = false, $analyzer = 'dclref-default-fr') {
        $mapping = self::stdIndexAndFilter($includeInAll, $analyzer);

        $mapping['fields']['suggest'] = [
            'type' => 'completion',
            'index_analyzer' => 'suggest', // utile ?
            'search_analyzer' => 'suggest', // utile ?
        ];

        return $mapping;
    }
}
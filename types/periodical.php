<?php
/**
 * This file is part of the 'Docalist Biblio' plugin.
 *
 * Copyright (C) 2012, 2013 Daniel Ménard
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

/**
 * Décrit un périodique : revue, magazine, journal, newsletter, etc.
 *
 * - Une publication périodique, ou simplement un périodique, est un titre de
 *   presse qui paraît régulièrement. Les périodiques sont généralement
 *   imprimés. Cependant, il existe aussi depuis quelques années des
 *   périodiques électroniques, à consulter sur Internet, sur un assistant
 *   personnel ou sur une liseuse.
 *   @see http://fr.wikipedia.org/wiki/Publication_p%C3%A9riodique
 *
 * - Periodical literature (also called a periodical publication or simply a
 *   periodical) is a published work that appears in a new edition on a regular
 *   schedule. The most familiar examples are the newspaper, often published
 *   daily, or weekly; or the magazine, typically published weekly, monthly
 *   or as a quarterly. Other examples would be a newsletter, a literary
 *   journal or learned journal, or a yearbook.
 *   @see http://en.wikipedia.org/wiki/Periodical_literature
 *
 * Types de périodiques :
 * - revue : périodique spécialisé dans un domaine précis
 *
 * - magasine : périodique, le plus souvent illustré, traitant de divers
 *   sujets ou parfois spécialisé
 *
 * - journal : document qui recense par ordre chronologique ou thématique un
 *   certain nombre d'événements pour une période donnée (généralement une
 *   journée, d'où le nom). Par extension, un journal désigne une publication
 *   regroupant des articles sur l'actualité du jour.
 *
 * @see http://fr.wikipedia.org/wiki/Journal @see http://en.wikipedia.org/wiki/Newspaper
 * @see http://fr.wikipedia.org/wiki/Revue
 * @see http://fr.wikipedia.org/wiki/Magazine @see http://en.wikipedia.org/wiki/Magazine
 *
 * Principales caractéristiques :
 * - a un éditeur (un organisme)
 * - a un ISSN
 * - a une périodicité
 */
// @formatter:off
return [
    'name' => 'periodical',
    'label' => __('Périodique', 'docalist-biblio'),
    'description' => __('Une publication périodique (revue, magazine, journal...)', 'docalist-biblio'),
    'fields' => [

        // Type, Genre, Media
        ['name' => 'group', 'label' => 'Nature du document'],
        ['name' => 'genre', 'table' => ['genres-periodical']],
        ['name' => 'media', 'table' => ['medias']], // papier, web, archives sur dvd

        // Title, OtherTitle, Translation
        ['name' => 'group', 'label' => 'Titres'],
        ['name' => 'title'],
        ['name' => 'issn'], // issn en ligne ? repétable ? double issn ?
        ['name' => 'othertitle', 'table' => ['titles']], // oui avec table
        // ['name' => 'translation', 'table' => ['languages']],

        // Author, Organisation
        ['name' => 'group', 'label' => 'Auteurs'],
        ['name' => 'author', 'table' => ['roles-author'], 'format' => 'fmt1'], // dir de pub, rédac chef, relations pub, conseil d'orientation, CS, comité de rédaction
        ['name' => 'organisation', 'table' => ['countries', 'roles-organisation']], // exemple : asso auteur d'une revue

        // Date / Language / Pagination / Format
        ['name' => 'group', 'label' => 'Informations bibliographiques'],
        ['name' => 'date'],
        ['name' => 'language', 'table' => ['languages']],
        ['name' => 'pagination'], // nombre moyen de pages par numéro
        ['name' => 'format'], // taille, couleur,
        // ['name' => 'doi'],

        // Editor / Collection / Edition / Isbn
        ['name' => 'group', 'label' => 'Informations éditeur'],
        ['name' => 'editor', 'table' => ['countries']],
        ['name' => 'collection'],

        // Topic / Abstract / Note
        ['name' => 'group', 'label' => 'Indexation et résumé'],
        ['name' => 'topic', 'table' => ['prisme', 'names', 'geo', 'free']],
        ['name' => 'abstract', 'table' => ['languages']],
        ['name' => 'note', 'table' => ['notes']],

        // Liens et relations
        ['name' => 'group', 'label' => 'Liens et relations'],
        ['name' => 'link', 'table' => ['links']],
        ['name' => 'relations', 'table' => ['relations']],

        // Ref / Owner / Creation / Lastupdate
        ['name' => 'group', 'label' => 'Informations de gestion'],
        ['name' => 'type'],
        ['name' => 'ref'],
        ['name' => 'owner'],
        ['name' => 'creation'],
        ['name' => 'lastupdate'],
    ]
];
// @formatter:on
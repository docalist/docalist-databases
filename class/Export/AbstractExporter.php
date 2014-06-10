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
namespace Docalist\Biblio\Export;

use Docalist\Biblio\Entity\Reference;
use Docalist\Biblio\Entity\ReferenceIterator;
use Docalist\Forms\Fragment;

/**
 * Classe de base pour les exporteurs.
 *
 * Principe :
 * - Chaque exporteur est un objet qui sait exporter des notices dans un
 *   format ou une famille de formats.
 * - La méthode principale est la fonction export() qui prend en paramètre
 *   un itérateur de références et se charge de générer le fichier d'export
 *   sur la sortie standard.
 * - Chaque exporteur peut avoir des settings.
 * - Plus tard, il sera possible de créer un nouvel exporteur directement
 *   depuis le back-office en paramétrant une classe existante (choix de la
 *   classe de base, saisie des paramètres).
 * - Pour cela, les exporteurs disposent d'une méthode settingsForm() qui
 *   retourne le formulaire de paramétrage de cet exporteur.
 * - Lorsque l'utilisateur valide, la méthode validateSettings() est appelée
 *   et les paramètres obtenus sont enregistrés.
 * - Lorsqu'un exporteur est créé, docalist-biblio lui passe en paramètre les
 *   paramètres enregistrés.
 * - Enfin, la méthode get() est un helper qui permet de récupérer une option
 *   de configuration.
 */
abstract class AbstractExporter {

    /**
     * Les paramètres de l'exporteur.
     *
     * @var array
     */
    protected $settings;

    /**
     * Retourne le formulaire de paramètrage de l'exporteur.
     *
     * @return Fragment
     */
    public static function settingsForm() {
        return null;
    }

    /**
     * Valide les settings de l'exporteur.
     *
     * Cette méthode est appellée quand le formulaire retourné par
     * settingsForm() est soumis par l'utilisateur. Les données transmises
     * correspondent au tableau $_POST contenant les données de la requête.
     *
     * La méthode doit extraire et valider les settings et retourner le
     * tableau obtenu.
     *
     * @param array $settings Les données brutes ($_POST).
     *
     * @return array Les settings validés.
     */
    public static function validateSettings(array $settings) {
        return $settings;
    }

    /**
     * Initialise l'exporteur.
     *
     * @param array $settings Les paramètres de l'exporteur, tels que retournés
     * par validateSettings().
     */
    public function __construct(array $settings = []) {
        $this->settings = $settings;
    }

    /**
     * Retourne un paramètre de l'exporteur.
     *
     * @param string $setting Nom de l'option.
     * @param mixed $default Valeur par défaut retournée si le paramètre indiqué
     * n'existe pas dans les paramètres.
     *
     * @return mixed
     */
    public function get($setting, $default = null) {
        if (array_key_exists($setting, $this->settings)) {
            return $this->settings[$setting];
        }

        return $default;
    }

    /**
     * Exporte le lot de notices passé en paramètre.
     *
     * @param ReferenceIterator $references Un itérateur contenant les
     * notices à exporter.
     */
    abstract public function export(ReferenceIterator $references);
}
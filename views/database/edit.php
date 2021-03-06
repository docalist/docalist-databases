<?php
/**
 * This file is part of Docalist Data.
 *
 * Copyright (C) 2012-2019 Daniel Ménard
 *
 * For copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Docalist\Data\Views;

use Docalist\Data\Pages\AdminDatabases;
use Docalist\Data\Settings\DatabaseSettings;
use Docalist\Forms\Form;

/**
 * Edite les paramètres d'un base de données.
 *
 * @var AdminDatabases $this
 * @var DatabaseSettings $database La base à éditer.
 * @var int $dbindex L'index de la base.
 * @var string $error Erreur éventuelle à afficher.
 *
 * @author Daniel Ménard <daniel.menard@laposte.net>
 */
?>
<div class="wrap">
    <h1><?= __('Paramètres de la base', 'docalist-data') ?></h1>

    <p class="description">
        <?= __('Utilisez le formulaire ci-dessous pour modifier les paramètres de votre base de données :', 'docalist-data') ?>
    </p>

    <?php if ($error) :?>
        <div class="error">
            <p><?= $error ?></p>
        </div>
    <?php endif ?>

    <?php
        // TODO : Choix de l'analyseur par défaut : désactivé pour le moment
        // Le code actuel utilise la méthode IndexManager::getIndexSettings() qui n'est plus dispo
        // A revoir quand on pourra paramétrer plusieurs moteurs de recherche

        // Récupère les settings pour déterminer la liste des analyseurs disponibles
        // $settings = docalist('docalist-search-index-manager')->getIndexSettings();

        // Ne conserve que les analyseurs "texte"
        // $analyzers = [];
        // foreach(array_keys($settings['settings']['analysis']['analyzer']) as $analyzer) {
        //     if (strpos($analyzer, 'text') !== false) {
        //         $analyzers[] = $analyzer;
        //     }
        // }

        $form = new Form();

        $form->tag('h2.title', __('Paramètres généraux', 'docalist-data'));
        $form->tag('p', __('Options de publication de votre base de données.', 'docalist-data'));
        $form->input('name')
             ->addClass('regular-text')
             ->setDescription(__('Nom de code interne de la base de données, de 1 à 14 caractères, lettres minuscules, chiffres et tiret autorisés.', 'docalist-data'));
        $form->select('homepage')
             ->setOptions(pagesList())
             ->setFirstOption(false)
             ->setDescription(__("Choisissez la page d'accueil de votre base. Les références auront un permalien de la forme <code>/votre/page/12345/</code>.", 'docalist-data'));
        $form->select('homemode')
             ->setLabel(__("La page d'accueil affiche", 'docalist-data'))
             ->setOptions([
                 'page'     => __('Le contenu de la page WordPress', 'docalist-data'),
                 'archive'  => __('Une archive WordPress de toutes les références', 'docalist-data'),
                 'search'   => __('Une recherche docalist-search "*"', 'docalist-data')
             ])
             ->setFirstOption(false)
             ->setDescription(__("Choisissez ce qui doit être affiché lorsque vous visitez la page d'accueil de votre base.", 'docalist-data'));
        $form->select('searchpage')
             ->setOptions(pagesList())
             ->setFirstOption(false);

        $form->tag('h2.title', __('Fonctionnalités', 'docalist-data'));
        $form->tag('p', __('Options et fonctionnalités disponibles pour cette base.', 'docalist-data'));
        $form->checkbox('thumbnail');
        $form->checkbox('revisions');
        $form->checkbox('comments');

        // $form->tag('h2.title', __('Indexation docalist-search', 'docalist-data'));
        // $form->tag('p', __("Options d'indexation dans le moteur de recherche.", 'docalist-data'));
        // $form->select('stemming')
        //      ->addClass('regular-text')
        //      ->setFirstOption(__('(Pas de stemming)', 'docalist-data'))
        //      ->setOptions($analyzers);

        $form->tag('h2.title', __('Intégration dans WordPress', 'docalist-data'));
        $form->tag('p', __("Apparence de cette base dans le back-office de WordPress.", 'docalist-data'));
        $form->input('icon')
             ->addClass('medium-text')
             ->setDescription(sprintf(
                __('Icône à utiliser dans le menu de WordPress. Par exemple %s pour obtenir l\'icône %s.<br />
                    Pour choisir une icône, allez sur le site %s, faites votre voix et recopiez le nom de l\'icône.<br />
                    Remarque : vous pouvez également indiquer l\'url complète d\'une image, mais dans ce cas celle-ci ne s\'adaptera pas automatiquement au back-office de WordPress.',
                    'docalist-data'),
                '<code>dashicons-book</code>',
                '<span class="dashicons dashicons-book"></span>',
                '<a href="https://developer.wordpress.org/resource/dashicons/#book" target="_blank">WordPress dashicons</a>'
            ));
        $form->input('label')
             ->addClass('regular-text');
        $form->textarea('description')
             ->setAttribute('rows', 2)
             ->addClass('large-text');

        $form->tag('h2.title', __('Autres informations', 'docalist-data'));
        $form->tag('p', __('Informations pour vous.', 'docalist-data'));
        $form->input('creation')
             ->setAttribute('disabled');
        $form->input('lastupdate')
             ->setAttribute('disabled');
        $form->textarea('notes')
             ->setAttribute('rows', 10)
             ->addClass('large-text')
             ->setDescription(__("Vous pouvez utiliser cette zone pour stocker toute information qui vous est utile : historique, modifications apportées, etc.", 'docalist-data'));

        $form->submit(__('Enregistrer les modifications', 'docalist-data'))
            ->addClass('button button-primary');

        !isset($database->creation) && $database->creation = date_i18n('Y/m/d H:i:s');
        !isset($database->lastupdate) && $database->lastupdate = date_i18n('Y/m/d H:i:s');
        !isset($database->stemming) && $database->stemming = 'fr-text';
        !isset($database->icon) && $database->icon = 'dashicons-list-view';
        !isset($database->thumbnail) && $database->thumbnail = true;
        !isset($database->revisions) && $database->revisions = true;
        !isset($database->comments) && $database->comments = false;

        $form->bind($database);
        $form->display('wordpress');
    ?>
</div>
<script type="text/javascript">
(function($) {
    /**
     * Si la base n'a pas de slug, change le slug quand on tape le nom
     */
    $(document).ready(function () {
        $(document).on('input propertychange', '#icon', function() {
            $('#icon-preview').remove();
            $('#icon').after('<span id="icon-preview" class="dashicons ' + $('#icon').val() + '" style="padding-left: 10px;font-size: 30px;"></span>');
        });
        $('#icon').trigger('input');

        $('#name').focus();
    });
}(jQuery));
</script>
<?php
/**
 * Retourne la liste hiérarchique des pages sous la forme d'un tableau
 * utilisable dans un select.
 *
 * @return array Un tableau de la forme PageID => PageTitle
 */
function pagesList() {
    $pages = ['…'];
    foreach(get_pages() as $page) { /* @var \WP_Post $page */
        $pages[$page->ID] = str_repeat('   ', count($page->ancestors)) . $page->post_title;
    }

    return $pages;
}

<?php
/**
 * This file is part of Docalist Databases.
 *
 * Copyright (C) 2012-2017 Daniel Ménard
 *
 * For copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */
namespace Docalist\Databases\Views;

use Docalist\Databases\Pages\AdminDatabases;
use Docalist\Databases\Settings\DatabaseSettings;
use Docalist\Databases\Settings\TypeSettings;

/**
 * Affiche la liste des bases de données existantes.
 *
 * @var AdminDatabases $this
 * @var DatabaseSettings[] $databases Liste des bases de données.
 *
 * @author Daniel Ménard <daniel.menard@laposte.net>
 */
?>
<style>
div.dbdesc{
    white-space: pre-wrap;
    max-height: 10em;
    overflow-y: auto;
}
</style>
<div class="wrap">
    <h1><?= __('Gestion des bases Docalist', 'docalist-databases') ?></h1>

    <p class="description">
        <?= __('Voici la liste de vos bases Docalist :', 'docalist-databases') ?>
    </p>

    <table class="widefat fixed">

    <thead>
        <tr>
            <th><?= __('Nom de la base', 'docalist-databases') ?></th>
            <th><?= __('Page d\'accueil', 'docalist-databases') ?></th>
            <th><?= __('Types de notices', 'docalist-databases') ?></th>
            <th><?= __('Nombre de notices', 'docalist-databases') ?></th>
            <th><?= __('Description', 'docalist-databases') ?></th>
        </tr>
    </thead>

    <?php
    $nb = 0;
    foreach($databases as $dbindex => $database) { /** @var DatabaseSettings $database */
        $edit = esc_url($this->url('DatabaseEdit', $dbindex));
        $delete = esc_url($this->url('DatabaseDelete', $dbindex));
        $listTypes = esc_url($this->url('TypesList', $dbindex));
        $exportSettings = esc_url($this->url('DatabaseExportSettings', $dbindex));
        $importSettings = esc_url($this->url('DatabaseImportSettings', $dbindex));

        $count = wp_count_posts($database->postType())->publish;
        $listRefs = esc_url(admin_url('edit.php?post_type=' . $database->postType()));
        $nb++; ?>

        <tr>
            <td class="column-title">
                <strong>
                    <a href="<?= $edit ?>"><?= $database->label() ?></a>
                </strong>
                <div class="row-actions">
                    <span class="edit">
                        <a href="<?= $edit ?>">
                            <?= __('Paramètres', 'docalist-databases') ?>
                        </a>
                    </span>
                    |
                    <span class="list-types">
                        <a href="<?= $listTypes ?>">
                            <?= __('Types de notices', 'docalist-databases') ?>
                        </a>
                    </span>
                    |
                    <span class="delete">
                        <a href="<?= $delete ?>">
                            <?= __('Supprimer', 'docalist-databases') ?>
                        </a>
                    </span>
                    <br />
                    <span class="export-settings">
                        <a href="<?= $exportSettings ?>">
                            <?= __('Exporter paramètres', 'docalist-databases') ?>
                        </a>
                    </span>
                    |
                    <span class="import-settings">
                        <a href="<?= $importSettings ?>">
                            <?= __('Importer paramètres', 'docalist-databases') ?>
                        </a>
                    </span>
                </div>
            </td>

            <td><a href="<?= $database->url() ?>"><?= $database->slug() ?></a></td>
            <td>
                <?php if (0 === count($database->types)): ?>
                    <a href="<?= esc_url($this->url('TypeAdd', $dbindex)) ?>">
                        <?= __('Ajouter un type...', 'docalist-databases') ?>
                    </a>
                <?php else: ?>
                    <?php foreach ($database->types as $typeindex => $type): /** @var TypeSettings $type */ ?>
                        <a href="<?= esc_url($this->url('GridList', $dbindex, $typeindex)) ?>">
                            <?= $type->label() ?>
                        </a>
                        <br />
                    <?php endforeach ?>
                <?php endif ?>

            </td>
            <td><a href="<?= $listRefs ?>"><?= $count ?></a></td>
            <td><div class="dbdesc"><?= $database->description() ?></div></td>
        </tr>
        <?php
    } // end foreach

    if ($nb === 0) : ?>
        <tr>
            <td colspan="4">
                <em><?= __('Aucune base définie.', 'docalist-databases') ?></em>
            </td>
        </tr><?php
    endif; ?>

    </table>

    <p>
        <a href="<?= esc_url($this->url('DatabaseAdd')) ?>" class="button button-primary">
            <?= __('Créer une base...', 'docalist-databases') ?>
        </a>
    </p>
</div>

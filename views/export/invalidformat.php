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

namespace Docalist\Data\Export\Views;

use Docalist\Data\Export\ExportService;

/**
 * Affiche un message "format invalide"
 *
 * @var ExportService   $this
 *
 * @author Daniel Ménard <daniel.menard@laposte.net>
 */
$this->setTitle(__("Format d'export invalide", 'docalist-data')); ?>

<p class="export-intro"><?php
    _e(
        "Les contenus sélectionnés ne peuvent pas être exportés avec le format d'export indiqué.",
        'docalist-data'
    ); ?>
</p>

<p class ="export-back">
    <a href="javascript:history.back()"><?php
        _e('« Retour à la page précédente', 'docalist-data'); ?>
    </a>
</p>

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

namespace Docalist\Data\Field;

use Docalist\Data\Type\TypedPostalAddress;

/**
 * Champ standard "address" : adresse postale.
 *
 * Ce champ permet de saisir les adresses postales d'une entité.
 *
 * Chaque occurence comporte deux sous-champs :
 * - `type` : type d'adresse,
 * - `value` : adresse.
 *
 * Le sous-champ type est associé à une table d'autorité qui indique les types d'adresses disponibles
 * ("table:address-type" par défaut).
 *
 * @author Daniel Ménard <daniel.menard@laposte.net>
 */
class AddressField extends TypedPostalAddress
{
    public static function loadSchema(): array
    {
        return [
            'name' => 'address',
            'repeatable' => true,
            'fields' => [
                'type' => [
                    'table' => 'table:postal-address-type',
                ],
                'value' => [
                    'fields' => [
                        'country' => [
                            'table' => 'table:ISO-3166-1_alpha2_fr',
                        ],
                    ],
                ],
            ],
        ];
    }
}

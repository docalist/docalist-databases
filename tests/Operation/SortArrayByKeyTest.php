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

namespace Docalist\Data\Tests\Operation;

use PHPUnit_Framework_TestCase;
use Docalist\Data\Operation\SortArrayByKey;

/**
 * Teste le filtre SortArrayByKey.
 *
 * @author Daniel Ménard <daniel.menard@laposte.net>
 */
class SortArrayByKeyTest extends PHPUnit_Framework_TestCase
{
    /**
     * Fournit des exemples de données à traiter et le résultat attendu.
     *
     * @return array[]
     */
    public function dataProvider()
    {
        return [
            // Trie les noms de champs par ordre alpha
            [
                ['b' => 'B', 'a' => 'A'],
                ['a' => 'A', 'b' => 'B']
            ],

            // Ne tient pas compte de la casse des caractères
            [
                ['b' => 'B', 'C' => 'C'],
                ['C' => 'C', 'b' => 'B']
            ],

            // Ne trie pas les sous-champs
            [
                ['b' => ['b2' => 'B2', 'b1' => 'B1'], 'a' => ['a2' => 'A2', 'a1' => 'A1']],
                ['a' => ['a2' => 'A2', 'a1' => 'A1'], 'b' => ['b2' => 'B2', 'b1' => 'B1']],
            ],
        ];
    }

    /**
     * Teste le filter.
     *
     * @param mixed $data
     * @param mixed $result
     *
     * @dataProvider dataProvider
     */
    public function testProcess($data, $result)
    {
        // Crée le filtre
        $filter = new SortArrayByKey();

        // Vérifie que le filter est un callable
        $this->assertTrue(is_callable($filter));

        // Vérifie que le filter retourne bien le résultat attendu
        $this->assertSame($result, $filter($data));
    }
}

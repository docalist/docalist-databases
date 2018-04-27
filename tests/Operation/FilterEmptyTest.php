<?php declare(strict_types=1);
/**
 * This file is part of Docalist Data.
 *
 * Copyright (C) 2012-2018 Daniel Ménard
 *
 * For copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */
namespace Docalist\Data\Tests\Operation;

use PHPUnit_Framework_TestCase;
use Docalist\Data\Operation\FilterEmpty;

/**
 * Teste le filtre FilterEmpty.
 *
 * @author Daniel Ménard <daniel.menard@laposte.net>
 */
class FilterEmptyTest extends PHPUnit_Framework_TestCase
{
    /**
     * Fournit des exemples de données à traiter et le résultat attendu.
     *
     * @return array[]
     */
    public function dataProvider()
    {
        return [
            [ [     ]    ,  null        ],
            [ [ 'a' ]    ,  [ 'a' ]     ],
            [ [ []  ]    ,  [ []  ]     ],
            [ 'not array',  'not array' ],
            [ ''         ,  null        ],
        ];
    }

    /**
     * Teste le filtre.
     *
     * @param mixed $data
     * @param mixed $result
     *
     * @dataProvider dataProvider
     */
    public function testProcess($data, $result)
    {
        // Crée le filtre
        $filter = new FilterEmpty();

        // Vérifie que le filter est un callable
        $this->assertTrue(is_callable($filter));

        // Vérifie que le filter retourne bien le résultat attendu
        $this->assertSame($result, $filter($data));
    }
}

<?php
/**
 * This file is part of Docalist Data.
 *
 * Copyright (C) 2012-2018 Daniel Ménard
 *
 * For copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */
namespace Docalist\Data\Tests\Export\Exporter;

use PHPUnit_Framework_TestCase;
use Docalist\Data\Export\Writer\JsonWriter;

/**
 * Teste la classe JsonWriter.
 *
 * @author Daniel Ménard <daniel.menard@laposte.net>
 */
class JsonWriterTest extends PHPUnit_Framework_TestCase
{
    public function testPretty()
    {
        $writer = new JsonWriter();

        $this->assertFalse($writer->getPretty());

        $writer->setPretty(true);
        $this->assertTrue($writer->getPretty());

        $writer->setPretty(false);
        $this->assertFalse($writer->getPretty());

        $writer->setPretty('1'); // falsy
        $this->assertTrue($writer->getPretty());

        $writer->setPretty('0'); // falsy
        $this->assertFalse($writer->getPretty());
    }

    public function testGetContentType()
    {
        $writer = new JsonWriter();
        $this->assertSame('application/json; charset=utf-8', $writer->getContentType());
    }

    public function testIsBinaryContent()
    {
        $writer = new JsonWriter();
        $this->assertFalse($writer->isBinaryContent());
    }

    public function testSuggestFilename()
    {
        $writer = new JsonWriter();
        $this->assertSame('export.json', $writer->suggestFilename());
    }

    public function testExportToString()
    {
        // Mode compact
        $writer = new JsonWriter();
        $this->assertSame('[{"a":"A"}]', $writer->exportToString([['a'=>'A']]));

        // Mode pretty
        $writer->setPretty(true);
        $this->assertSame("[\n{\n    \"a\": \"A\"\n}\n]", $writer->exportToString([['a'=>'A']]));

        // Vérifie que accents, slashs sont non échappés
        $writer->setPretty(false);
        $this->assertSame('[{"é":"/"}]', $writer->exportToString([['é'=>'/']]));
        // et non pas '[{"\u00e9":"\/"}]'
    }

    /**
     * Vérifie qu'une exception est générée si on ne passe pas un handle de fichier correct à export().
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid stream
     */
    public function testExportInvalidStream()
    {
        $writer = new JsonWriter();
        $writer->export(null, [['a'=>'']]);
    }

    /**
     * Vérifie qu'une exception est générée si on ne passe pas un handle de fichier ouvert en écriture.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage not writable
     */
    public function testExportNotWritableStream()
    {
        $writer = new JsonWriter();
        $writer->export(fopen('php://temp', 'r'), [['a'=>'']]);
    }
}
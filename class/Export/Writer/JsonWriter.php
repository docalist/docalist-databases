<?php
/**
 * This file is part of Docalist Data.
 *
 * Copyright (C) 2012-2018 Daniel Ménard
 *
 * For copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */
namespace Docalist\Data\Export\Writer;

use Docalist\Data\Export\Writer;

/**
 * Générateur JSON pour l'export de données Docalist.
 *
 * @author Daniel Ménard <daniel.menard@laposte.net>
 */
class JsonWriter extends AbstractWriter
{
    /**
     * Génère ou non du JSON indenté et formatté.
     *
     * @var bool
     */
    protected $pretty = false;

    /**
     * Modifie l'option "pretty" qui indique s'il faut génèrer ou non du JSON indenté et formatté.
     *
     * @param bool $pretty
     *
     * @return self
     */
    public function setPretty($pretty)
    {
        $this->pretty = (bool) $pretty;

        return $this;
    }

    /**
     * Indique si on génère ou non du JSON indenté et formatté.
     *
     * @return boolean
     */
    public function getPretty()
    {
        return $this->pretty;
    }

    public function getContentType()
    {
        return 'application/json; charset=utf-8';
    }

    public function isBinaryContent()
    {
        return false;
    }

    public function suggestFilename()
    {
        return 'export.json';
    }

    public function export($stream, Iterable $records)
    {
        $this->checkIsWritableStream($stream);

        $pretty = $this->getPretty();

        $options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        $pretty && $options |= JSON_PRETTY_PRINT;

        $first = true;
        $this->write($stream, $pretty ? "[\n" : '[');
        $comma = $pretty ? ",\n" : ',';
        foreach ($records as $record) {
            $data = $first ? '' : $comma;
            $data .= json_encode($record, $options);
            $this->write($stream, $data);
            $first = false;
        }
        $this->write($stream, $pretty ? "\n]" : ']');
    }
}

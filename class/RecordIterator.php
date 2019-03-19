<?php declare(strict_types=1);
/**
 * This file is part of Docalist Data.
 *
 * Copyright (C) 2012-2019 Daniel Ménard
 *
 * For copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */
namespace Docalist\Data;

use Docalist\Search\SearchRequest;
use Docalist\Search\SearchResponse;
use Iterator;
use Countable;

/**
 * Un itérateur de références (pour l'export).
 *
 * @author Daniel Ménard <daniel.menard@laposte.net>
 */
class RecordIterator implements Iterator, Countable
{
    /**
     * Taille des batchs
     *
     * @var integer
     */
    const BATCH_SIZE = 1000;

    /**
     * La requête en cours.
     *
     * @var SearchRequest
     */
    protected $searchRequest;

    /**
     * Les résultats en cours.
     *
     * @var SearchResponse
     */
    protected $searchResponse;

    /**
     * Les hits de la page actuelle.
     *
     * @var array
     */
    protected $hits;

    /**
     * L'index du hit en cours.
     *
     * @var int
     */
    protected $current;

    /**
     * Nombre maximum de notices à itérer.
     *
     * @var int
     */
    protected $limit;

    /**
     * Nombre de hits déjà itérés.
     *
     * @var int
     */
    protected $count;

    /**
     * Construit l'itérateur.
     *
     * @param SearchRequest $searchRequest
     * @param int $limit Nombre maximum de notices à itérer (zéro = pas de limite).
     */
    public function __construct(SearchRequest $searchRequest, int $limit = 0)
    {
        $this->searchRequest = $searchRequest;
        $this->searchRequest->setSize($limit === 0 ? self::BATCH_SIZE : min($limit, self::BATCH_SIZE));
        $this->limit = $limit;
        $this->count = 0;
    }

    public function rewind()
    {
        $this->loadPage(1);
    }

    public function valid()
    {
        if ($this->current >= count($this->hits)) {
            return false;
        }

        if (0 !== $this->limit && $this->count >= $this->limit) {
            return false;
        }

        return true;
    }

    public function current()
    {
        return docalist('docalist-data')->getRecord($this->key());
    }

    public function key()
    {
        return $this->hits[$this->current]->_id;
    }

    public function next()
    {
        ++$this->current;
        ++$this->count;
        if (! $this->valid()) {
            if ($this->searchResponse && $this->count < $this->searchResponse->getHitsCount()) {
                $this->loadPage($this->searchRequest->getPage() + 1);
            }
        }
    }

    /**
     * Charge une page de résultats.
     *
     * @param int $page La page à charger.
     */
    protected function loadPage($page)
    {
        $this->searchRequest->setPage($page);
        $this->searchResponse = $this->searchRequest->execute();
        $this->hits = $this->searchResponse->getHits();
        $this->current = 0;
    }

    /**
     * Retourne la requête docalist-search en cours.
     *
     * @deprecated Utiliser getSearchRequest().
     *
     * @return SearchRequest
     */
    public function searchRequest()
    {
        _deprecated_function(__METHOD__, '0.15', 'getSearchRequest');

        return $this->getSearchRequest();
    }

    /**
     * Retourne la requête docalist-search en cours.
     *
     * @return SearchRequest
     */
    public function getSearchRequest()
    {
        return $this->searchRequest;
    }

    /**
     * Retourne le nombre de notices dans l'itérateur.
     *
     * @return int
     */
    public function count()
    {
        is_null($this->searchResponse) && $this->rewind();

        return $this->searchResponse->getHitsCount();
    }
}

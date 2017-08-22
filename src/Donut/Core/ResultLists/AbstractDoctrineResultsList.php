<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace Angelov\Donut\Core\ResultLists;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Angelov\Donut\Core\ResultLists\Pagination\PaginatableResultsInterface;
use Angelov\Donut\Core\ResultLists\Sorting\OrderField;
use Angelov\Donut\Core\ResultLists\Sorting\SortableResultsInterface;

abstract class AbstractDoctrineResultsList implements PaginatableResultsInterface, SortableResultsInterface
{
    private $offset;
    private $perPage;
    private $orderByFields = [];

    public function setOffset(int $offset) : void
    {
        $this->offset = $offset;
    }

    public function getOffset() : int
    {
        return $this->offset;
    }

    public function setItemsPerPage(int $items) : void
    {
        $this->perPage = $items;
    }

    public function getItemsPerPage() : int
    {
        return $this->perPage;
    }

    /**
     * @param OrderField[] $fields
     */
    public function orderBy(array $fields) : void
    {
        $this->orderByFields = $fields;
    }

    /**
     * @return OrderField[]
     */
    public function getOrderFields() : array
    {
        return $this->orderByFields;
    }

    public function countTotal() : int
    {
        return count(new Paginator($this->prepareQuery()));
    }

    public function getResults() : array
    {
        $query = $this->prepareQuery();

        $paginator = new Paginator($query);

        $query->setFirstResult($this->offset);
        $query->setMaxResults($this->perPage);

        return $paginator->getIterator()->getArrayCopy();
    }

    abstract protected function prepareQuery() : Query;
}

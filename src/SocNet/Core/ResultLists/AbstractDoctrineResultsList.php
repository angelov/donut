<?php

namespace SocNet\Core\ResultLists;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use SocNet\Core\ResultLists\Pagination\PaginatableResultsInterface;
use SocNet\Core\ResultLists\Sorting\OrderField;
use SocNet\Core\ResultLists\Sorting\SortableResultsInterface;

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

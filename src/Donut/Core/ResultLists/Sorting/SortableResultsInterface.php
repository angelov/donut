<?php

namespace Angelov\Donut\Core\ResultLists\Sorting;

interface SortableResultsInterface
{
    /**
     * @param OrderField[] $fields
     */
    public function orderBy(array $fields) : void;

    /**
     * @return OrderField[]
     */
    public function getOrderFields() : array;
}

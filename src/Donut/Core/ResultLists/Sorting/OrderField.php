<?php

namespace Angelov\Donut\Core\ResultLists\Sorting;

class OrderField
{
    private $field;
    private $direction;

    public function __construct(string $field, string $direction = OrderDirection::DESC)
    {
        $this->ensureDirectionIsValid($direction);

        $this->field = $field;
        $this->direction = $direction;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function getField(): string
    {
        return $this->field;
    }

    private function ensureDirectionIsValid(string $direction): void
    {
        if (!in_array($direction, [OrderDirection::DESC, OrderDirection::ASC])) {
            throw new InvalidOrderDirectionException($direction);
        }
    }
}

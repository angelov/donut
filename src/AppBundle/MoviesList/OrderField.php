<?php

namespace AppBundle\MoviesList;

class OrderField
{
    private $field;
    private $direction;

    public function __construct(string $field, string $direction = OrderDirection::DESC)
    {
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
}

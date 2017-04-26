<?php

namespace SocNet\Core\ResultLists\Sorting;

class InvalidOrderDirectionException extends \Exception
{
    private $direction;

    public function __construct(string $direction)
    {
        $this->direction = $direction;

        parent::__construct(sprintf(
            '%s is not a valid ordering direction. Please check the "%s" class for valid directions.',
            $direction,
            OrderDirection::class
        ));
    }

    public function getDirection() : string
    {
        return $this->direction;
    }
}

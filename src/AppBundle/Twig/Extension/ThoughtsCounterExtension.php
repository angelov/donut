<?php

namespace AppBundle\Twig\Extension;

use AppBundle\Entity\User;
use AppBundle\ThoughtsCounter\ThoughtsCounterInterface;

class ThoughtsCounterExtension extends \Twig_Extension
{
    private $counter;

    public function __construct(ThoughtsCounterInterface $counter)
    {
        $this->counter = $counter;
    }

    public function getFunctions() : array
    {
        return [
            new \Twig_SimpleFunction('count_thoughts', [$this, 'countThoughtsForUser'])
        ];
    }

    public function countThoughtsForUser(User $user) : string
    {
        return (string) $this->counter->count($user);
    }

    public function getName()
    {
        return 'count_thoughts';
    }
}

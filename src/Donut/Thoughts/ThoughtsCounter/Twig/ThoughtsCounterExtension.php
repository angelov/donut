<?php

namespace Angelov\Donut\Thoughts\ThoughtsCounter\Twig;

use Angelov\Donut\Users\User;
use Angelov\Donut\Thoughts\ThoughtsCounter\ThoughtsCounterInterface;

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
}
